<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(isset($captcha_needed) and !$captcha_verified) require ROOT.'include/code/code_verify_captcha.php';

if(isset($password_check_needed)) {//password_check_needed
	if(!isset($_POST['password'])) $err_msgs[]=$password_msg=func::tr('Sorry, but entering Admin password is needed!');
	else {
		$password=$_POST['password'];
		if($password==='') $err_msgs[]=func::tr('Password field is empty!');
		else if(!isset($captcha_err)) {
			unset($captcha_verified);
			if(isset($_SESSION['reg8log']['captcha_verified'])) unset($_SESSION['reg8log']['captcha_verified']);
			if(strpos($_POST['password'], "hashed-$site_salt")!==0) $_POST['password']='hashed-'.$site_salt.'-'.hash('sha256', $site_salt.$_POST['password']);
			require ROOT.'include/code/code_verify_password.php';
			if(isset($err_msgs)) {
				$try_type='password';
				require ROOT.'include/code/code_update_user_last_ch_try.php';
			}
			else if(isset($_COOKIE['reg8log_ch_pswd_try'])) {
				if(is_numeric($_COOKIE['reg8log_ch_pswd_try'])) {
					$query='update `accounts` set `ch_pswd_tries`=`ch_pswd_tries`-'.$_COOKIE['reg8log_ch_pswd_try'].' where `username`='.$reg8log_db->quote_smart($identified_user)." and `ch_pswd_tries`>={$_COOKIE['reg8log_ch_pswd_try']} limit 1";
					$reg8log_db->query($query);
				}
				setcookie('reg8log_ch_pswd_try', false, mktime(12,0,0,1, 1, 1990), '/', null, HTTPS, true);
			}
		}
	}
}//password_check_needed

?>