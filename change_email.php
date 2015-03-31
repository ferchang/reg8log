<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
define('CAN_INCLUDE', true);

require 'include/common.php';

require ROOT.'include/code/code_identify.php';

if(!isset($identified_user)) func::my_exit('<center><h3>'.func::tr('You are not authenticated msg').'.</h3><a href="index.php">'.func::tr('Login page').'</a></center>');

$_fields=config::get('fields');

$email_format=$_fields['email'];

$try_type='email';
require ROOT.'include/code/code_check_captcha_needed4user.php';

if(!isset($captcha_needed)) {
	$try_type='password';
	require ROOT.'include/code/code_check_captcha_needed4user.php';
}

if(isset($captcha_needed)) {
	require ROOT.'include/code/sess/code_sess_start.php';
	$captcha_verified=isset($_SESSION['reg8log']['captcha_verified']);
}

if(isset($_POST['password'], $_POST['newemail'], $_POST['reemail'])) {

	require ROOT.'include/code/code_prevent_repost.php';

	require ROOT.'include/code/code_prevent_xsrf.php';

	
	
	if(isset($captcha_needed) and !$captcha_verified) require ROOT.'include/code/code_verify_captcha.php';
	
	if($_POST['password']==='') $err_msgs[]=func::tr('Password field is empty!');
	else if(!isset($captcha_err)) {
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
	
	if(func::utf8_strlen($_POST['newemail'])<$email_format['minlength']) $err_msgs[]=sprintf(func::tr('new email is shorter than2'), $email_format['minlength']);
	else if(func::utf8_strlen($_POST['newemail'])>$email_format['maxlength'])	$err_msgs[]=sprintf(func::tr('new email is longer than2'), $email_format['maxlength']);
	else if($email_format['php_re'] and $_POST['newemail']!=='' and !preg_match($email_format['php_re'], $_POST['newemail'])) $err_msgs[]=func::tr('New email is invalid!');
	else if($_POST['newemail']!==$_POST['reemail']) $err_msgs[]=func::tr('email fields aren\'t match!');
	else if(!isset($err_msgs)) {
		if(isset($_SESSION['reg8log']['captcha_verified'])) unset($_SESSION['reg8log']['captcha_verified']);
		$captcha_verified=false;
		$captcha_needed=true;
		$try_type='email';
		require ROOT.'include/code/code_update_user_last_ch_try.php';
		$field_name='email';
		$except_user=$identified_user;
		$field_value=$_POST['newemail'];
		require ROOT.'include/code/code_check_field_uniqueness.php';
	}
	
	if(!isset($err_msgs)) {
		if($identified_user==='Admin') config::set('email_change_needs_email_verification', config::get('admin_email_change_needs_email_verification'));
		if(!config::get('email_change_needs_email_verification') or (config::get('email_change_needs_email_verification')===2 and !config::get('email_verification_needed'))) {
			require ROOT.'include/code/code_change_email.php';
			require ROOT.'include/code/code_set_submitted_forms_cookie.php';
			$success_msg='<h3>'.func::tr('Your email changed successfully').'.</h3>';
			$no_specialchars=true;
			require ROOT.'include/page/page_success.php';
			exit;
		}
		//---------------
		require ROOT.'include/code/code_add_change_email_request.php';
		
		if(isset($max_emails_reached)) {
			if(config::get('lang')==='fa') $failure_msg='<h3>'.sprintf(func::tr('max emails reached msg'), func::duration2friendly_str($verification_time, 0), $max_emails).'.</h3>';
			else $failure_msg='<h3>'.sprintf(func::tr('max emails reached msg'), $max_emails, func::duration2friendly_str($verification_time, 0)).'.</h3>';
			$no_specialchars=true;
			require ROOT.'include/page/page_failure.php';
			exit;
		}
		$success_msg='<h3>'.sprintf(func::tr('verification email sent msg3'), $_POST['newemail'], func::duration2friendly_str($verification_time, 0)).'.</h3>';
		$no_specialchars=true;
		require ROOT.'include/page/page_success.php';
		exit;
		//---------------
	}
}

require ROOT.'include/page/page_change_email_form.php';

?>