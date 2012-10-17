<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
$parent_page=true;

$index_dir='';

require $index_dir.'include/common.php';

require $index_dir.'include/code/code_encoding8anticache_headers.php';

require $index_dir.'include/code/code_identify.php';

if(!isset($identified_user)) exit('<center><h3>You are not authenticated! <br>First log in.</h3><a href="index.php">Login page</a></center>');

if(!$allow_users2disable_account_block and $identified_user!='Admin') exit('<center><h3>Changing brute-force protection setting is not allowed!</h3></center>');

if(!isset($site_salt)) if(isset($_COOKIE['reg8log_site_salt'])) $site_salt=$_COOKIE['reg8log_site_salt'];
else {
	require $index_dir.'include/code/code_fetch_site_vars.php';
	setcookie('reg8log_site_salt', $site_salt, 0, '/', null, $https, true);
}

$try_type='password';
require $index_dir.'include/code/code_check_captcha_needed4user.php';

if(isset($captcha_needed)) {
	require $index_dir.'include/code/code_sess_start.php';
	$captcha_verified=isset($_SESSION['captcha_verified']);
}

if(isset($_POST['protection'], $_POST['password'])) {

require $index_dir.'include/code/code_prevent_repost.php';
require $index_dir.'include/code/code_prevent_xsrf.php';

$protection=$_POST['protection'];
if(!in_array($protection, array(0, 1, 2, 3))) exit('Invalid protection value!');

if(isset($captcha_needed) and !$captcha_verified) require $index_dir.'include/code/code_verify_captcha.php';

$password=$_POST['password'];
if($password=='') $err_msgs[]='Password field is empty!';
else if(!isset($captcha_err)) {
		if(strpos($_POST['password'], "hashed-$site_salt")!==0) $_POST['password']='hashed-'.$site_salt.'-'.hash('sha256', $site_salt.$_POST['password']);
		require $index_dir.'include/code/code_verify_password.php';
		if(isset($err_msgs)) {
			$try_type='password';
			require $index_dir.'include/code/code_update_user_last_ch_try.php';
		}
		else if(isset($_COOKIE['reg8log_ch_pswd_try'])) {
			$query='update `accounts` set `ch_pswd_tries`=`ch_pswd_tries`-'.$reg8log_db->quote_smart($_COOKIE['reg8log_ch_pswd_try']).' where `username`='.$reg8log_db->quote_smart($identified_user).' limit 1';
			$reg8log_db->query($query);
			setcookie('reg8log_ch_pswd_try', false, mktime(12,0,0,1, 1, 1990), '/', null, $https, true);
		}
	}

if(!isset($err_msgs)) {
	require $index_dir.'include/code/code_change_brute_force_protection.php';
	$success_msg='<h3>Brute-force protection setting changed successfully.</h3>';
	$no_specialchars=true;
	require $index_dir.'include/page/page_success.php';
	require $index_dir.'include/code/code_set_submitted_forms_cookie.php';
	exit;
}

}

require $index_dir.'include/page/page_change_brute_force_setting_form.php';

?>
