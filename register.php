<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
define('CAN_INCLUDE', true);

$store_request_entropy_probability2=1;
// I set store_request_entropy_probability2 to 1 on this page, because this page contains the register form and thus its requests have precious entropy (passwords, usernames, ...). Also the traffic on this page is only a tiny fraction of all of the site traffic and thus its entropy update queries contribute to the database overload proportionally less.
// note: this variable must be set before including common.php, because code_gather_request_entropy.php is included in common.php.

require 'include/common.php';

require ROOT.'include/code/code_prevent_repost.php';

if(!config::get('registeration_enabled')) func::my_exit('<center><h3>'.func::tr('Registration is disabled!').'</h3><a href="index.php">'.func::tr('Login page').'</a></center>');

require ROOT.'include/code/sess/code_sess_start.php';

$captcha_verified=isset($_SESSION['reg8log']['captcha_verified']);

require ROOT.'include/code/code_identify.php';

if(isset($identified_user)) func::my_exit('<center><h3>'.func::tr('Error: You are logged in!').'</h3><a href="index.php">'.func::tr('Login page').'</a></center>');

$_fields=config::get('fields');

$err_msgs=null;

if(!empty($_POST)) {//Post data (registration fields values) is received

require ROOT.'include/code/code_prevent_xsrf.php';

if(isset($_COOKIE['reg8log_register_sess_salt'])) $session_salt=$_COOKIE['reg8log_register_sess_salt'];
else {
	$session_salt=func::random_string(22);
	setcookie('reg8log_register_sess_salt', $session_salt, 0, '/', null, HTTPS, true);
}

require ROOT.'include/code/code_validate_register_submit.php';

if($err_msgs) require ROOT.'include/page/page_register_form.php';
else {//Data validated

if(strpos($_POST['password'], "encrypted-$site_salt")===0) {
	
	$_POST['password']=func::decrypt(base64_decode(substr($_POST['password'], strrpos($_POST['password'], '-')+1)));
}
else if(strpos($_POST['password'], "hashed-$site_salt")!==0) $_POST['password']='hashed-'.$site_salt.'-'.hash('sha256', $site_salt.$_POST['password']);

if(config::get('email_verification_needed') or config::get('admin_confirmation_needed')) {
	require ROOT.'include/code/code_add_pending_account.php';
	if(config::get('admin_confirmation_needed')) {
		$pending_reg=true;
		require ROOT.'include/code/log/code_log_registeration.php';
	}
}
else {
	require ROOT.'include/code/code_add_account.php';
	if(config::get('login_upon_register')) {
	  $_username=$_POST['username'];
	  require ROOT.'include/code/code_login_upon_register.php';
	  $success_msg.='(<span style="color: blue">'.func::tr('You are logged in automatically').'</span>)<br>';
	}
	require ROOT.'include/code/log/code_log_registeration.php';
}

require ROOT.'include/code/code_set_submitted_forms_cookie.php';

require ROOT.'include/code/code_reset_ajax_check_usernames.php';

require ROOT.'include/page/page_success.php';

}//Data validated
}//Post data (registration fields values) is received
else require ROOT.'include/page/page_register_form.php';

?>