<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$captcha_verified=false;
if(isset($_SESSION['reg8log']['captcha_verified'])) unset($_SESSION['reg8log']['captcha_verified']);

if(config::get('admin_operations_require_password')>1) {
	if(!isset($password_check_needed) or isset($_POST['remember'])) {
		
		$password_check_key=func::random_string(22);
		$query='update `admin` set ';
		if(isset($password_check_needed)) $query.="`last_password_check`=".REQUEST_TIME.", ";
		$query.="`password_check_key`='$password_check_key' limit 1";
		$reg8log_db->query($query);
		setcookie('reg8log_password_check_key', $password_check_key, 0, '/', null, HTTPS, true);
		$_COOKIE['reg8log_password_check_key']=$password_check_key;
		unset($password_check_needed, $captcha_needed);
	}
}

?>