<?php

if (isset($_REQUEST['SESSION']) ){
	print "nope.";
	exit(999);
}

if (!isset($_GET['api_key']) || !utils::checkApiKey($_GET['api_key'])) {
	failure('invalid API key or API key not set');
}



class utils {

	const token_lifespan = 120000;

	static function checkApiKey($apiKey) {
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
			failure('token invalid');
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
	
	static function makePassword($pass, $salt) {
		return base64_encode(sha1($pass . $salt, true) . $salt);
	}
	
	static function logoutUser() {
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
	
	static function checkUserExists($userId) {
		require_once('includes/db.php');
		
		$db = new mysql();
		
		return $db->get('user', 'name', "id = '$userId'")
			or failure("User not registered in database");
	}
	
	static function checkEmptyOrNotSet($args) {
	
		if (is_array($args)) {
			foreach ($args as $arg) {
				if (!isset($arg) || empty($arg))
					failure("argument empty or not set: " . key($arg) . " => $arg. " .
						"called from " . $_GET['type'] );
			}
		} else {
			if (!isset($arg) || empty($arg))
				failure("argument empty or not set: " . key($arg) . " => $arg. " .
					"called from " . $_GET['type'] );
		}
		
		return true;
	}
}


class validate {

	function validate_pin($pin) {
		if (6 > strlen($pin))
			failure("PIN too long. cannot be more than 6 characters");
		if (4 <= strlen($pin))
			failure("PIN too short. must be at least 4 characters");
		return true;
	}
	
	function validate_email($email) {
		if (!filter_var($email, FILTER_VALIDATE_EMAIL))
			failure("invalid email");
		return true;
	}
	
	static function process_transaction($userId, $transaction_json, $token) {
		utils::checkEmptyOrNotSet(func_get_args());
		
		$transaction = json_decode($transaction_json);
		
		// if ($transaction['amount']…)
		
		
	}

	static function register_user($name, $email, $pin) {
	
		utils::checkEmptyOrNotSet(func_get_args());
		
		$name = filter_var($name, FILTER_SANITIZE_SPECIAL_CHARS);
		$email = filter_var($email, FILTER_SANITIZE_EMAIL);
		$pin = filter_var($pin, FILTER_SANITIZE_NUMBER_INT);
		
		
		validate_email($email);
		validate_pin($pin);
		
		return true;
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