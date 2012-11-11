<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
$parent_page=true;

$index_dir='';

require $index_dir.'include/common.php';

require $index_dir.'include/code/code_encoding8anticache_headers.php';

require $index_dir.'include/code/code_identify.php';

if(!isset($identified_user)) exit('<center><h3>You are not authenticated! <br>First log in.</h3><a href="index.php">Login page</a></center>');

require $index_dir.'include/config/config_register.php'; //for password_refill

require $index_dir.'include/config/config_register_fields.php';

$password_format=$fields['password'];

if(!isset($site_salt)) if(isset($_COOKIE['reg8log_site_salt'])) $site_salt=$_COOKIE['reg8log_site_salt'];
else {
	require $index_dir.'include/code/code_fetch_site_vars.php';
	setcookie('reg8log_site_salt', $site_salt, 0, '/', null, $https, true);
}

$try_type='password';
require $index_dir.'include/code/code_check_captcha_needed4user.php';

if(isset($captcha_needed)) {
	require $index_dir.'include/code/sess/code_sess_start.php';
	$captcha_verified=isset($_SESSION['captcha_verified']);
}

if(isset($_POST['curpass'], $_POST['newpass'], $_POST['repass'])) {

	require $index_dir.'include/code/code_prevent_repost.php';

	require $index_dir.'include/code/code_prevent_xsrf.php';

	require_once $index_dir.'include/func/func_utf8.php';

	if(isset($captcha_needed) and !$captcha_verified) require $index_dir.'include/code/code_verify_captcha.php';
	
	if($_POST['curpass']==='') $err_msgs[]='current password field is empty!';
	else if(!isset($captcha_err)) {
		if(strpos($_POST['curpass'], "hashed-$site_salt")!==0) $_POST['curpass']='hashed-'.$site_salt.'-'.hash('sha256', $site_salt.$_POST['curpass']);
		require $index_dir.'include/code/code_verify_password.php';
		if(isset($err_msgs)) {
			$try_type='password';
			require $index_dir.'include/code/code_update_user_last_ch_try.php';
		}
		else if(isset($_COOKIE['reg8log_ch_pswd_try'])) {
			if(is_numeric($_COOKIE['reg8log_ch_pswd_try'])) {
				$query='update `accounts` set `ch_pswd_tries`=`ch_pswd_tries`-'.$_COOKIE['reg8log_ch_pswd_try'].' where `username`='.$reg8log_db->quote_smart($identified_user)." and `ch_pswd_tries`>={$_COOKIE['reg8log_ch_pswd_try']} limit 1";
				$reg8log_db->query($query);
			}
			setcookie('reg8log_ch_pswd_try', false, mktime(12,0,0,1, 1, 1990), '/', null, $https, true);
		}
	}

	if(strpos($_POST['newpass'], "hashed-$site_salt")!==0 and strpos($_POST['newpass'], "encrypted-$site_salt")!==0) {
		if(utf8_strlen($_POST['newpass'])<$password_format['minlength']) {
			$err_msgs[]="new password is shorter than {$password_format['minlength']} characters!";
			$password_error=true;
		}
		else if(utf8_strlen($_POST['newpass'])>$password_format['maxlength']) {
			$err_msgs[]="new password is longer than {$password_format['maxlength']} characters!";
			$password_error=true;
		}
		else if($password_format['php_re'] and $_POST['newpass']!=='' and !preg_match($password_format['php_re'], $_POST['newpass'])) {
			$err_msgs[]="New password is invalid!";
			$password_error=true;
		}
		else if($_POST['newpass']!==$_POST['repass']) {
			$err_msgs[]="password fields aren't match!";
			$password_error=true;
		}
	}
	else {
		if($_POST['newpass']!==$_POST['repass']) {
			$err_msgs[]="password fields aren't match!";
			$password_error=true;
		}
		else if(strpos($_POST['newpass'], "encrypted-$site_salt")===0) {
			require_once $index_dir.'include/func/func_site8client_keys_hmac_verifier.php';
			if(!verify_hmac(base64_decode(substr($_POST['newpass'], strrpos($_POST['newpass'], '-')+1)))) {
				$err_msgs[]="error in password decryption!";
				$password_error=true;
			}
		}
	}

	if(!isset($err_msgs)) {
		if(strpos($_POST['newpass'], "encrypted-$site_salt")===0) {
			require_once $index_dir.'include/func/func_encryption_with_site8client_keys.php';
			$_POST['newpass']=decrypt(base64_decode(substr($_POST['newpass'], strrpos($_POST['newpass'], '-')+1)));
		}
		else if(strpos($_POST['newpass'], "hashed-$site_salt")!==0) $_POST['newpass']='hashed-'.$site_salt.'-'.hash('sha256', $site_salt.$_POST['newpass']);
		$_username=$identified_user;
		require $index_dir.'include/config/config_password_change_or_reset.php';
		require $index_dir.'include/code/code_change_password.php';
		require $index_dir.'include/code/code_set_submitted_forms_cookie.php';
		$success_msg='<h3>Your password changed successfully.</h3>';
		$no_specialchars=true;
		require $index_dir.'include/page/page_success.php';
		exit;
	}
}

require $index_dir.'include/page/page_change_password_form.php';

?>