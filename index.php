<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
$parent_page=true;

$index_dir='';

// All codes created with Notepad++
//Thanks for such a lightweight, fast and powerful tool with excellent features.

require $index_dir.'include/common.php';

require $index_dir.'include/code/code_encoding8anticache_headers.php';

if(!isset($site_salt)) if(isset($_COOKIE['reg8log_site_salt'])) $site_salt=$_COOKIE['reg8log_site_salt'];
else {
	require $index_dir.'include/code/code_fetch_site_vars.php';
	setcookie('reg8log_site_salt', $site_salt, 0, '/', null, $https, true);
}

if(isset($_POST['username'], $_POST['password']) and $_POST['username']!=='' and $_POST['password']!=='') { //login attempt

	require $index_dir.'include/code/code_prevent_repost.php';

	require $index_dir.'include/code/code_prevent_xsrf.php';

	if(strpos($_POST['password'], "hashed-$site_salt")!==0) $_POST['password']='hashed-'.$site_salt.'-'.hash('sha256', $site_salt.$_POST['password']);

	$_POST['username']=str_replace(array('ي', 'ك'), array('ی', 'ک'), $_POST['username']);
	
	$manual_login=array('username'=>$_POST['username'], 'password'=>$_POST['password']);

	require $index_dir.'include/config/config_brute_force_protection.php';

	$_username=$_POST['username'];
	require $index_dir.'include/code/code_check_ip_block.php';
	if(isset($ip_block)) {
		require $index_dir.'include/page/page_ip_block.php';
		exit;
	}
	
	$_username=$_POST['username'];
	require $index_dir.'include/code/code_check_account_block.php';
	if(isset($account_block)) {
		require $index_dir.'include/page/page_account_block.php';
		exit;
	}
	
	if(isset($captcha_needed)) {
		require $index_dir.'include/code/code_verify_captcha.php';
		if(isset($captcha_err)) if(!isset($captcha_msg)) $err_msg=$err_msgs[0];
	}
	
} //login attempt

if(isset($_POST['remember'])) $remember=true;
else $remember=false;

if(isset($_POST['login2ip'])) $login2ip=true;
else $login2ip=false;

if(!isset($captcha_err)) {
	$pass_banned_user=true;
	require $index_dir.'include/code/code_identify.php';
	if(isset($identify_error)) {
		$failure_msg=($debug_mode)? $user->err_msg : 'Identification error';
		require $index_dir.'include/page/page_failure.php';
		exit;
	}
}
else {
	require $index_dir.'include/page/page_login_form.php';
	exit;
}

if(isset($identified_user)) {//Identified

if(isset($manual_login)) {

$_identified_username=$identified_user;

require $index_dir.'include/code/dec/code_dec_incorrect_logins.php';

if($remember) $user->save_identity('permanent');
else $user->save_identity('session');

$msg='<h1>You logged in successfully <span style="white-space: pre; color: #155;">'.htmlspecialchars($identified_user, ENT_QUOTES, 'UTF-8').'</span>.</h1>';

}
else $msg='<h1>Hello <span style="white-space: pre; color: #155;">'.htmlspecialchars($identified_user, ENT_QUOTES, 'UTF-8').'</span>.<br />You are logged in.</h1>';

require $index_dir.'include/page/page_members_area.php';

}//Identified
else if(isset($pending_user)) {
	if(isset($manual_login)) {
		$_identified_username=$pending_user;

		require $index_dir.'include/code/dec/code_dec_incorrect_logins.php';
		
	}
	require $index_dir.'include/code/code_detect8fix_failed_activation.php';
	require $index_dir.'include/page/page_pending_user.php';
}
else if(isset($banned_user)) {
	if(isset($manual_login)) {
		$_identified_username=$banned_user;

		require $index_dir.'include/code/dec/code_dec_incorrect_logins.php';
		
		if($remember) $user->save_identity('permanent');
		else $user->save_identity('session');
	}
	require $index_dir.'include/page/page_banned_user.php';
}
else {//Not identified
	if(isset($manual_login)) {
		require $index_dir.'include/code/code_set_submitted_forms_cookie.php';
		$err_msg='You are not authenticated!<br />Check your login information.';
		require $index_dir.'include/code/account_incorrect_login.php';
		require $index_dir.'include/code/code_ip_incorrect_login.php';
		if(isset($ip_block)) {
			require $index_dir.'include/page/page_ip_block.php';
			exit;
		}
		if(isset($account_block)) {
			require $index_dir.'include/page/page_account_block.php';
			exit;
		}
	}
	require $index_dir.'include/page/page_login_form.php';
}//Not identified
