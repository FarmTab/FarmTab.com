<?php

require_once('includes/db.php');
require_once('includes/secrets.php');
require_once('includes/utils.php');

session_start();
$response = array();


// NUR FOR TEST
$_SESSION['valid'] = true;
$_SESSION['farm'] = Farm::find(1);
// NUR FOR TEST

header('Content-Type: application/json charset=UTF-8');
$request_method = $_SERVER['REQUEST_METHOD'];

if (isset($_GET['type'])) {

  if (!in_array(strtolower($_GET['type']), array("login","logout")))
    utils::check_login();

  switch(strtolower($_GET['type'])) {
    case 'farm':
      $response = farm();
      break;
    case 'customer':
      $response = customer();
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
    case 'validate':
      $response = validate_pin($_POST['userId'], $_POST['pin']);
      break;
    default:
      failure("unrecognized API call");
  }
  
  $response['status'] = 'success';
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

  $farm = Farm::find_by_email_and_pass($email, $cryptedPass)->to_array(array(
    'include' => array('venues')
  ));
  
  $was_login_successful = !$farm->errors;
  $db->update('login_attempts', array('login_successful' => $was_login_successful), "`id`='$login_id'" );
  if (!$farm) failure('Could not log in');
  
  session_regenerate_id (); // for security
  
  $response['status'] = 'success';
  $response['data'] = $farm;
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

function farm() {

  switch ($request_method) {
    case 'GET':
      //$response['data'] =
        //Customer::find_all_by_farm($_SESSION['farm']->id)->to_array(array(
        //  'include' => array('tabs')
        //));
      $response['data'] = current_farm()->customers()->to_array(array(
        'include' => array('tabs')
      ));
      break;
    case 'POST':
      break;
    

  }

  return $response;
}

function customer() {
  $farmId = current_farm()->id;

  switch($request_method) {
    case 'POST':
      return register_user($_POST['name'], $_POST['email'], $_POST['PIN']);
      break;
    case 'GET':
      $response['data'] = Customer::find($_GET['user_id']);
      return $response;
      break;
    case 'PUT':
      $customer = Customer::find($_GET['user_id']);
      $customer->update_attributes(json_decode($_POST['user']))
            or failure("Could not update attributes: " . $customer->errors);
      break;
  }
}


function process_transaction($userId, $transaction_json, $token) {
  
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

function validate_pin($userId, $test_pin) {
  
  $customer = Customer::find($userId);
  
  $customer->check_pin($test_pin)
    or auth_failure("Authentication failure, PIN invalid");
    
  $token = utils::setToken($userId);
    
  $response['status'] = 'success';
  $response['data'] = array(
    'balance' => $result['balance'],
    'token' => $token,
    'timeout' => time() + utils::token_lifespan
  );
  
  return $response; 
}



function current_farm() {
  return $_SESSION['farm'];
}

function register_user($name, $email, $pin) {

  $customer = new Customer();
  $customer->name  = $name;
  $customer->email = $email;
  $customer->pin   = $pin; // automatically encrypts

  $customer.save();

  if ($customer->$errors) {
    log("Customer registration error: " . $customer->errors);
    failure("Could not register customer");
  }
  
  $response['status'] = 'success';
  $response['data'] = $customer;
  
  return $response;
}

?>
