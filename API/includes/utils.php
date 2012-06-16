<?php

if (isset($_REQUEST['SESSION']) ){
  print "nope.";
  exit(999);
}


// NUR FOR TEST
$_COOKIE['api_key'] = "124df26asdf";
// /NUR FOR TEST
if (!isset($_COOKIE['api_key']) || !utils::check_api_key($_COOKIE['api_key'])) {
  failure('invalid API key or API key not set');
}


class utils {

  const token_lifespan = 120000;

  static function check_api_key($api_key) {
    require_once('db.php');
    $db = new mysql();
    
    return $db->get('api_clients', 'client_name', "api_key = '$api_key'");
  }
  
  static function check_login() {
    if (!isset($_SESSION['valid']) || !$_SESSION['valid'])
    	failure('Authentication error');
    
    return true;
  }
  
  static function check_token($token) {
    if (!isset($_SESSION['token']) || $token !== $_SESSION['token'])
      failure('token invalid');
    else if (time() - $_SESSION['token_timestamp'] > token_lifespan)
      failure('token timeout: too old');
    return true;
  }
  
  /** @author AfroSoft <info@afrosoft.tk> */
  static function generate_salt() {
    $character_list = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $i = 0;
    $salt = "";
    do {
      $salt .= $character_list{mt_rand(0,strlen($character_list)-1)};
      $i++;
    } while ($i < 15);
    return $salt;
  }
  
  static function make_password($pass, $salt) {
    return base64_encode(hash('sha256', $pass . $salt, true) . $salt . secrets::SALT);
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
  
  static function set_token($userId) {
  
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

function auth_failure($message) {
  header('HTTP/1.0 401 Unauthorized');
  failure($message);
}

?>
