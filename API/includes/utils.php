<?php

if (isset($_REQUEST['SESSION']) ){
	print "nope.";
	exit(999);
}

if (!isset($_GET['api_key']) || !utils::check_api_key($_GET['api_key'])) {
	failure('invalid API key or API key not set');
}



class utils {

	const token_lifespan = 120000;

	static function check_api_key($apiKey) {
		require_once('db.php');
		$db = new mysql();
		
		return $db->get('api_clients', 'client_name', "api_key = '$apiKey'");
	}
	
	static function checkLogin() {
		//if (!isset($_SESSION['valid']) || !$_SESSION['valid'])
		//	failure('Authentication error');
		return true;
	}
	
	static function checkToken($token) {
		if (!isset($_SESSION['token']) || $token !== $_SESSION['token'])
			failure('token invalid')
		else if (time() - $_SESSION['token_timestamp'] > token_lifespan)
			failure('token timeout: too old');
		return true;
	}
	
	/** @author AfroSoft <info@afrosoft.tk> */
	static function generateSalt() {
		$characterList = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$i = 0;
		$salt = "";
		do {
			$salt .= $characterList{mt_rand(0,strlen($characterList)-1)};
			$i++;
		} while ($i < 15);
		return $salt;
	}
	
	static function make_password($pass, $salt) {
		return base64_encode(sha1($pass . $salt, true) . $salt);
	}
	
	static function logout_user() {
		$_SESSION = array();
		$params = session_get_cookie_params();
	    setcookie(session_name(), '', time() - 42000,
	        $params["path"], $params["domain"],
	        $params["secure"], $params["httponly"]
	    );
	    session_destroy();
	}
	
	static function setToken($userId) {
	
		session_start();
	
		$agent = $_SERVER['HTTP_USER_AGENT'];
		$agent .= 'SHIFLETT';
	
		$token = md5($agent . secrets::TOKEN_SECRET . $userId);
	
		$_SESSION['token_timestamp'] = time();
		$_SESSION['token'] = $token;
		return $_SESSION['token'];	
	}
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