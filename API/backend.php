<?php

require_once('includes/db.php');
require_once('includes/secrets.php');
require_once('includes/utils.php');

session_start();
$response = array();

header('Content-Type: application/json charset=UTF-8');

if (isset($_GET['type'])) {

	switch(strtolower($_GET['type'])) {
		case 'currentfarm':
			$response = get_current_farm();
			break;
		case 'linkuser':
			$response = link_user($_GET['userId']);
			break;
		case 'login':
			$response = attempt_login($_POST['email'], $_POST['password']);
			break;
		case 'logout':
			$response = attempt_logout();
			break;
		case 'registeruser':
			$response = register_user($_POST['name'], $_POST['email'], $_POST['pin']);
			break;
		case 'transaction':
			$response = process_transaction($_POST['userId'], $_POST['transaction'], $_POST['token']);
			break;
		case 'userlist':
			$response = get_users();
			break;
		case 'validate':
			$response = validate_pin($_POST['userId'], $_POST['pin']);
			break;
		default:
			failure("unrecognized API call");
	}
	
	print json_encode($response);
	exit();	
}


function attempt_login($email, $pass) {

	$db = new mysql();
	
	$login_id = $db->insert('login_attempts', array(
    'email' => $email,
    'request_user_agent' => $_SERVER['HTTP_USER_AGENT'],
    'request_ip' => $_SERVER['request_ip']
	));
	
	// db function validates, no worries about injections
	$salt = $db->get('farm', 'salt', "email=$email") or failure('Could not find farmer');
	$cryptedPass = utils::make_password($pass, $salt);
	
	$farm = $db->select(array(
			'table' => "farm",
			'fields' => "id",
			'condition' => "email=$email AND pass=$cryptedPass"
		));
	
	if (!$farm)	{
    $db->update('login_attempts', array('login_successful' => false), "`id`='$login_id'" );
    failure('Could not log in');
	} else {
    $db->update('login_attempts', array('login_successful' => true), "`id`='$login_id'");
  }
	
	session_regenerate_id (); // for security
	
	$response['status'] = 'success';
	$response['data'] = array(
			'farmId' => $farm['id']
	);
	return $response;
}

function attempt_logout() {

	utils::logoutUser();

	$response = array(
    	'result' => "success",
    	'data'   => array('message' => "logged out")
    );
    
    return $response;
}

function register_user($name, $email, $pin) {
	utils::checkLogin();
	validate::register_user($name, $email, $pin);

	$db = new mysql();

	$salt = utils::generateSalt();
	$cryptedPin = utils::makePassword($pin, $salt);
	
	$userId = $db->insert('user', array(
			'name'  => $name,
			'email' => $email,
			'pin'   => $cryptedPin,
			'salt'  => $salt
	)) or failure('could not register user');
	
	setup_xtab($userId, $db);
	
	$response['status'] = 'success';
	$response['data'] = array('userId' => $userId);
	
	return $response;
}

function setup_xtab($userId, $db) {

	$farmId = $_SESSION['farmId'];

	$db->insert('farm_x_user', array(
			'farm_id' => $farmId,
			'user_id' => $userId
	));
	
	$db->insert('tab', array(
			'farm_id' => $farmId,
			'user_id' => $userId,
			'balance' => "0.00"
	));
	
	$db->insert('user_x_tab', array(
			'user_id' => $userId,
			'tab_id' => 'LAST_INSERT_ID()'
	));
	
	if (mysql_error())
		failure("Couldn't insert into db: " . mysql_error());

}

function link_user($userId) {
	utils::checkLogin();
	
	setup_xtab($userId, $db);
	
	$response['status'] = 'success';
	$response['data'] = array('message' => "inserted successfully");
	
	return $response;	
}

function get_current_farm() {
	utils::checkLogin();
	
	$response['status'] = 'success';
	$response['data'] = array('farm_id' => $_SESSION['farmId']);
}

function get_users() {
	
	utils::checkLogin();
	
	$farmId = $_SESSION['farmId'];
	
	$db = new mysql();
	
	$users = $db->query(
			"SELECT user.id, user.name, user.img_url, tab.balance
			FROM user
			INNER JOIN farm_x_user fx
			    ON fx.user_id = user.id
			INNER JOIN tab
			    ON tab.user_id = user.id AND tab.farm_id = $farmId
			WHERE fx.farm_id = $farmId"
			, false, false);
			
	$response['status'] = 'success';
	$response['data'] = $users;
	
	return $response;
}

function get_balance($userId) {
	
	utils::checkLogin();
	
	$farmId = $_SESSION['farmId'];
	
	$db = new mysql();
	
	$bal = $db->get('tab','balance', "user_id='$userId' AND farm_id='$farmId'")
					or failure('could not find user balance');
	
	$response['status'] = "success";
	$response['data'] = array('balance' => $bal);
	
	return $response;
}


function process_transaction($userId, $transaction_json, $token) {
	
	utils::checkLogin();
	validate::process_transaction($userId, $transaction_json, $token);
	utils::checkToken($token);
	
	$transaction = json_decode($transaction_json);
	$farmId = $_SESSION['farmId'];
	
	$db = new mysql();
	
	$b = get_balance($userId);
	$currentBal = $b['data']['balance'];
	
	$newBal = $currentBal - $transaction['amount'];
	
	if ($newBal < 0)
		failure('Balance too low to process transaction');
		
	$db->insert('transaction', $transaction_json);
	$db->insert('user_x_transaction', array(
			'user_id' => $userId,
			'transaction_id' => "LAST_INSERT_ID()"
		));
	
	$db->update('tab', array('balance' => $newBal), "user_id='$userId' AND farm_id='$farmId'");
	
	$response['status'] = "success";
	$response['data'] = array('balance' => $newBal);
	
	return $response;
}

function validate_pin($userId, $pin) {
	
	utils::checkLogin();
	
	$db = new mysql();
	
	$result = $db->row(array(
			'table' => "user",
			'fields' => "pin, salt, balance",
			'condition' => "userId=$userId"
		)) or failure('Could not find user');
	$PIN1 = $result['pin'];
	$PIN2 = make_password($pin, $result['salt']);
	
	if ($PIN1 !== $PIN2)
		failure('Authentication failure, PIN invalid');
		
	$token = utils::setToken($userId);
		
	$response['status'] = 'success';
	$response['data'] = array(
		'balance' => $result['balance'],
		'token' => $token,
		'timeout' => time() + utils::token_lifespan
	);
	
	return $response; 
}

?>