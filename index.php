<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
define('CAN_INCLUDE', true);


// All codes created with Notepad++
//Thanks for such a lightweight, fast and powerful tool with excellent features.

require 'include/common.php';

require ROOT.'include/code/code_encoding8anticache_headers.php';

require ROOT.'include/code/code_set_site_salt.php';

if(isset($_POST['username'], $_POST['password']) and $_POST['username']!=='' and $_POST['password']!=='') { //login attempt

	require ROOT.'include/code/code_prevent_repost.php';

	require ROOT.'include/code/code_prevent_xsrf.php';

	if(strpos($_POST['password'], "hashed-$site_salt")!==0) $_POST['password']='hashed-'.$site_salt.'-'.hash('sha256', $site_salt.$_POST['password']);

	require_once ROOT.'include/func/func_yeh8kaaf.php';
	fix_yeh8kaaf($_POST['username']);
	
	$manual_login=array('username'=>$_POST['username'], 'password'=>$_POST['password']);

	require ROOT.'include/config/config_brute_force_protection.php';

	$_username=$_POST['username'];
	require ROOT.'include/code/code_check_ip_block.php';
	if(isset($ip_block)) {
		require ROOT.'include/page/page_ip_block.php';
		exit;
	}
	
	$_username=$_POST['username'];
	require ROOT.'include/code/code_check_account_block.php';
	if(isset($account_block)) {
		require ROOT.'include/page/page_account_block.php';
		exit;
	}
	
	if(isset($captcha_needed)) {
		require ROOT.'include/code/code_verify_captcha.php';
		if(isset($captcha_err)) if(!isset($captcha_msg)) $err_msg=$err_msgs[0];
	}
	
} //login attempt

if(isset($_POST['login2ip'])) $login2ip=true;
else $login2ip=false;

if(!isset($captcha_err)) {
	$pass_banned_user=true;
	require ROOT.'include/code/code_identify.php';
	if(isset($identify_error)) {
		$failure_msg=($debug_mode)? $user->err_msg : func::tr('Identification error');
		require ROOT.'include/page/page_failure.php';
		exit;
	}
}
else {
	require ROOT.'include/page/page_login_form.php';
	exit;
}

if(isset($identified_user)) {//Identified

if(isset($manual_login)) {

$_identified_username=$identified_user;

require ROOT.'include/code/dec/code_dec_incorrect_logins.php';

require_once ROOT.'include/code/code_save_login.php';

$msg='<h1>'.func::tr('You logged in successfully').' <span style="white-space: pre; color: #155;">'.htmlspecialchars($identified_user, ENT_QUOTES, 'UTF-8').'</span>.</h1>';

}
else $msg='<h1>'.func::tr('Hello').' <span style="white-space: pre; color: #155;">'.htmlspecialchars($identified_user, ENT_QUOTES, 'UTF-8').'</span>.<br />'.func::tr('You are logged in').'.</h1>';

require ROOT.'include/page/page_members_area.php';

}//Identified
else if(isset($pending_user)) {
	if(isset($manual_login)) {
		$_identified_username=$pending_user;

		require ROOT.'include/code/dec/code_dec_incorrect_logins.php';
		
	}
	require ROOT.'include/code/code_detect8fix_failed_activation.php';
	require ROOT.'include/page/page_pending_user.php';
}
else if(isset($banned_user)) {
	if(isset($manual_login)) {
		$_identified_username=$banned_user;

		require ROOT.'include/code/dec/code_dec_incorrect_logins.php';

		require_once ROOT.'include/code/code_save_login.php';

	}
	require ROOT.'include/page/page_banned_user.php';
}
else {//Not identified
	if(isset($manual_login)) {
		require ROOT.'include/code/code_set_submitted_forms_cookie.php';
		$err_msg=func::tr('Check login information msg');
		require ROOT.'include/code/code_account_incorrect_login.php';
		require ROOT.'include/code/code_ip_incorrect_login.php';
		if(isset($ip_block)) {
			require ROOT.'include/page/page_ip_block.php';
			exit;
		}
		if(isset($account_block)) {
			require ROOT.'include/page/page_account_block.php';
			exit;
		}
	}
	require ROOT.'include/page/page_login_form.php';
}//Not identified
