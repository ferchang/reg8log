<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
define('CAN_INCLUDE', true);

require 'include/common.php';

require ROOT.'include/code/code_identify.php';

if(!isset($identified_user)) func::my_exit('<center><h3>'.func::tr('You are not authenticated msg').'.</h3><a href="index.php">'.func::tr('Login page').'</a></center>');

if($identified_user==='Admin') config::set('change_autologin_key_upon_login', config::get('admin_change_autologin_key_upon_login'));
if(config::get('change_autologin_key_upon_login')===2 or (!config::get('allow_manual_autologin_key_change') and $identified_user!='Admin')) exit('<center><h3>Changing autologin key manually is not allowed!</h3></center>');

$try_type='password';
require ROOT.'include/code/code_check_captcha_needed4user.php';

if(isset($captcha_needed)) {
	require ROOT.'include/code/sess/code_sess_start.php';
	$captcha_verified=isset($_SESSION['reg8log']['captcha_verified']);
}

if(isset($_POST['password']) and $_POST['password']) {

require ROOT.'include/code/code_prevent_repost.php';
require ROOT.'include/code/code_prevent_xsrf.php';

if(isset($captcha_needed) and !$captcha_verified) require ROOT.'include/code/code_verify_captcha.php';

$password=$_POST['password'];
if($password==='') $err_msgs[]=func::tr('Password field is empty!');
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

if(!isset($err_msgs)) {
	require ROOT.'include/code/code_change_autologin_key.php';
	require ROOT.'include/code/code_set_submitted_forms_cookie.php';
	$success_msg='<h3>'.func::tr('The operation was performed successfully').'.</h3>';
	$no_specialchars=true;
	require ROOT.'include/page/page_success.php';
	exit;
}

}

require ROOT.'include/page/page_change_autologin_key_form.php';

?>
