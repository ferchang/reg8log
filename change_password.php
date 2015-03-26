<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
define('CAN_INCLUDE', true);

require 'include/common.php';

require ROOT.'include/code/code_encoding8anticache_headers.php';

require ROOT.'include/code/code_identify.php';

if(!isset($identified_user)) func::my_exit('<center><h3>'.func::tr('You are not authenticated msg').'.</h3><a href="index.php">'.func::tr('Login page').'</a></center>');

$_fields=config::get('fields');

$password_format=$_fields['password'];

require ROOT.'include/code/code_set_site_salt.php';

$try_type='password';
require ROOT.'include/code/code_check_captcha_needed4user.php';

if(isset($captcha_needed)) {
	require ROOT.'include/code/sess/code_sess_start.php';
	$captcha_verified=isset($_SESSION['reg8log']['captcha_verified']);
}

if(isset($_POST['curpass'], $_POST['newpass'], $_POST['repass'])) {

	require ROOT.'include/code/code_prevent_repost.php';

	require ROOT.'include/code/code_prevent_xsrf.php';

	if(isset($captcha_needed) and !$captcha_verified) require ROOT.'include/code/code_verify_captcha.php';
	
	if($_POST['curpass']==='') $err_msgs[]=func::tr('current password field is empty!');
	else if(!isset($captcha_err)) {
		if(strpos($_POST['curpass'], "hashed-$site_salt")!==0) $_POST['curpass']='hashed-'.$site_salt.'-'.hash('sha256', $site_salt.$_POST['curpass']);
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

	if(strpos($_POST['newpass'], "hashed-$site_salt")!==0 and strpos($_POST['newpass'], "encrypted-$site_salt")!==0) {
		if(func::utf8_strlen($_POST['newpass'])<$password_format['minlength']) {
			$err_msgs[]=sprintf(func::tr('new password is shorter than'), $password_format['minlength']);
			$password_error=true;
		}
		else if(func::utf8_strlen($_POST['newpass'])>$password_format['maxlength']) {
			$err_msgs[]=sprintf(func::tr('new password is longer than'), $password_format['maxlength']);
			$password_error=true;
		}
		else if($password_format['php_re'] and $_POST['newpass']!=='' and !preg_match($password_format['php_re'], $_POST['newpass'])) {
			$err_msgs[]=func::tr('New password is invalid!');
			$password_error=true;
		}
		else if($_POST['newpass']!==$_POST['repass']) {
			$err_msgs[]=func::tr('password fields aren\'t match!');
			$password_error=true;
		}
	}
	else {
		if($_POST['newpass']!==$_POST['repass']) {
			$err_msgs[]=func::tr('password fields aren\'t match!');
			$password_error=true;
		}
		else if(strpos($_POST['newpass'], "encrypted-$site_salt")===0) {
			
			if(!func::verify_hmac(base64_decode(substr($_POST['newpass'], strrpos($_POST['newpass'], '-')+1)))) {
				$err_msgs[]=func::tr('error in password decryption!');
				$password_error=true;
			}
		}
	}

	if(!isset($err_msgs)) {
		if(strpos($_POST['newpass'], "encrypted-$site_salt")===0) {
			
			$_POST['newpass']=func::decrypt(base64_decode(substr($_POST['newpass'], strrpos($_POST['newpass'], '-')+1)));
		}
		else if(strpos($_POST['newpass'], "hashed-$site_salt")!==0) $_POST['newpass']='hashed-'.$site_salt.'-'.hash('sha256', $site_salt.$_POST['newpass']);
		$_username=$identified_user;
		require ROOT.'include/code/code_change_password.php';
		require ROOT.'include/code/code_set_submitted_forms_cookie.php';
		$success_msg='<h3>'.func::tr('Your password changed successfully').'.</h3>';
		$no_specialchars=true;
		require ROOT.'include/page/page_success.php';
		exit;
	}
}

require ROOT.'include/page/page_change_password_form.php';

?>