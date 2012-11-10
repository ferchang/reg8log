<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
$parent_page=true;

$index_dir='';

$store_request_entropy_probability2=1;
// I set store_request_entropy_probability2 to 1 on this page, because this page contains the register form and thus its requests have precious entropy (passwords, usernames, ...). Also the traffic on this page is only a tiny fraction of all of the site traffic and thus its entropy update queries contribute to the database overload proportionally less.
// note: this variable must be set before including common.php, because code_gather_request_entropy.php is included in common.php.

require $index_dir.'include/common.php';

require $index_dir.'include/code/code_encoding8anticache_headers.php';

require $index_dir.'include/code/code_prevent_repost.php';

require $index_dir.'include/config/config_register.php';

if(!$registeration_enabled) exit('<center><h3>Registration is disabled!</h3></center>');

require $index_dir.'include/code/code_sess_start.php';

$captcha_verified=isset($_SESSION['captcha_verified']);

require $index_dir.'include/code/code_identify.php';

if(isset($identified_user)) exit('<center><h3>Error: You are logged in!</h3><a href="index.php">Login page</a></center>');

require $index_dir.'include/config/config_register_fields.php';

$err_msgs=null;

if(!isset($site_salt)) if(isset($_COOKIE['reg8log_site_salt'])) $site_salt=$_COOKIE['reg8log_site_salt'];
else {
	require $index_dir.'include/code/code_fetch_site_vars.php';
	setcookie('reg8log_site_salt', $site_salt, 0, '/', null, $https, true);
}

if(!empty($_POST)) {//Post data (registration fields values) is received

require $index_dir.'include/code/code_prevent_xsrf.php';

require_once $index_dir.'include/func/func_utf8.php';
require_once $index_dir.'include/func/func_random.php';

if(isset($_COOKIE['reg8log_register_sess_salt'])) $session_salt=$_COOKIE['reg8log_register_sess_salt'];
else {
	$session_salt=random_string(22);
	setcookie('reg8log_register_sess_salt', $session_salt, 0, '/', null, $https, true);
}

require $index_dir.'include/code/code_validate_register_submit.php';

if($err_msgs) require $index_dir.'include/page/page_register_form.php';
else {//Data validated

if(strpos($_POST['password'], "encrypted-$site_salt")===0) {
	require_once $index_dir.'include/func/func_encryption_with_site8client_keys.php';
	$_POST['password']=decrypt(base64_decode(substr($_POST['password'], strrpos($_POST['password'], '-')+1)));
}
else if(strpos($_POST['password'], "hashed-$site_salt")!==0) $_POST['password']='hashed-'.$site_salt.'-'.hash('sha256', $site_salt.$_POST['password']);

if($email_verification_needed or $admin_confirmation_needed) {
	require $index_dir.'include/code/code_add_pending_account.php';
	if($admin_confirmation_needed) {
		$pending_reg=true;
		require $index_dir.'include/code/code_log_registeration.php';
	}
}
else {
	require $index_dir.'include/code/code_add_account.php';
	if($login_upon_register) {
	  $_username=$_POST['username'];
	  require $index_dir.'include/code/code_login_upon_register.php';
	  $success_msg.='(<span style="color: blue">You are logged in automatically</span>)<br>';
	}
	require $index_dir.'include/code/code_log_registeration.php';
}

require $index_dir.'include/code/code_set_submitted_forms_cookie.php';

require $index_dir.'include/code/code_reset_ajax_check_usernames.php';

require $index_dir.'include/page/page_success.php';

}//Data validated
}//Post data (registration fields values) is received
else require $index_dir.'include/page/page_register_form.php';

?>