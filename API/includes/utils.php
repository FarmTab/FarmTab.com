<?php

if (isset($_REQUEST['SESSION']) ){
	print "nope.";
	exit(999);
}

if (!isset($_GET['api_key']) || !check_api_key($_GET['api_key'])) {
	failure('invalid API key or API key not set');
}





function logout_user() {
	$_SESSION = array();
	$params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
    session_destroy();
}

function check_api_key($apiKey) {
	require_once('db.php');
	$db = new mysql();
	
	return $db->get('api_clients', 'client_name', "api_key = '$apiKey'");
}

function checkLogin() {
	//if (!isset($_SESSION['valid']) || !$_SESSION['valid'])
	//	failure('Authentication error');
	return true;
}

function setToken($userId) {

	session_start();

	$agent = $_SERVER['HTTP_USER_AGENT'];
	$agent .= 'SHIFLETT';

	$token = md5($agent . secrets::TOKEN_SECRET . $userId);

	$_SESSION['token_timestamp'] = time();
	$_SESSION['token'] = $token;
	return $_SESSION['token'];	
}

function checkToken() {
	if (time() - $_SESSION['token_timestamp'] > 120000) // 2 minutes timeout
		return false;
	return true;
}

function make_password($pass, $salt) {
	return base64_encode(sha1($pass . $salt, true) . $salt);
}
	
	
/** @author AfroSoft <info@afrosoft.tk> */
function generateSalt() {
	$characterList = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	$i = 0;
	$salt = "";
	do {
		$salt .= $characterList{mt_rand(0,strlen($characterList)-1)};
		$i++;
	} while ($i < 15);
	return $salt;
}

function failure($message) {
	$response = array();
	$response['status'] = 'failure';
	$response['reason'] = $message;
	print json_encode($response);
	log('failure: ' . $message . implode(', ', $_POST));
	exit(9);
}


?>