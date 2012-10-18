<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
$parent_page=true;

$index_dir='';

$store_request_entropy_probability2=1;

require $index_dir.'include/common.php';

require $index_dir.'include/code/code_encoding8anticache_headers.php';

require $index_dir.'include/info/info_lockdown.php';

if(!$lockdown_bypass_system_enabled) exit('<h3 align="center">Lockdown bypass system is disabled by administrator!</h3>');

if(!isset($_GET['key'])) exit('<h3 align="center">Error: key parameter is not set!</h3>');

if(isset($_POST['remember'])) $remember=true;
else $remember=false;

if(!isset($site_salt)) if(isset($_COOKIE['reg8log_site_salt'])) $site_salt=$_COOKIE['reg8log_site_salt'];
else {
	require $index_dir.'include/code/code_fetch_site_vars.php';
	setcookie('reg8log_site_salt', $site_salt, 0, '/', null, $https, true);
}

if(isset($_POST['username']) and isset($_POST['password'])) {//1

require $index_dir.'include/code/code_prevent_repost.php';

$_POST['username']=str_replace(array('ي', 'ك'), array('ی', 'ک'), $_POST['username']);
	
$_POST['username']=strtolower($_POST['username']);

if(strpos($_POST['password'], "hashed-$site_salt")!==0) $_POST['password']='hashed-'.$site_salt.'-'.hash('sha256', $site_salt.$_POST['password']);

$manual_identify=array('username'=>$_POST['username'], 'password'=>$_POST['password']);

if($lockdown_bypass_system_enabled==1 and $_POST['username']!='admin') exit('<h3 align="center">Currently, lockdown bypass system is enabled only for the admin account!</h3>');

if($lockdown_bypass_system_enabled==2 and $_POST['username']=='admin') exit('<h3 align="center">Currently, lockdown bypass system is disabled for the admin account!</h3>');

$_username=$_POST['username'];
require $index_dir.'include/code/code_check_account_lockdown.php';

if(!isset($lockdown)) {

	if(!$lockdown_bypass_system_also4ip_lockdown) exit('<h3 align=center><span style="white-space: pre; color: #0a8;">'.htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8').'</span>\'s account is not locked!</h3><center><a href="index.php">Login page</a></center>');
	
	require $index_dir.'include/code/code_check_ip_lockdown.php';
	
	if(!isset($ip_lockdown)) exit('<h3 align=center>Neither of the account <span style="white-space: pre; color: #0a8;">'.htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8').'</span> and IP <span style="white-space: pre; color: #0a8;">'.$_SERVER['REMOTE_ADDR'].'</span> are locked!</h3><center><a href="index.php">Login page</a></center>');

}

require_once $index_dir.'include/code/code_db_object.php';

$_username=$reg8log_db->quote_smart($_POST['username']);
$query="select * from `lockdown_bypass` where `username`=$_username limit 1";

if(!$reg8log_db->result_num($query)) exit('<h3 align="center">Error: No record for '.htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8').' was found in the lockdown_bypass table!</h3>');

$rec=$reg8log_db->fetch_row();
$key=$rec['key'];

if($_GET['key']!==$key) exit('<h3 align="center">Error: Your lockdown bypass link was not verified!</h3>');

unset($identified_user);
unset($identify_error);

require $index_dir.'include/code/code_identify.php';

if(isset($identify_error)) {
	$failure_msg=($debug_mode)? $user->err_msg : 'Identification error';
	require $index_dir.'include/page/page_failure.php';
	exit;
}

if(isset($identified_user)) {//2

$_identified_username=$identified_user;
require $index_dir.'include/code/code_dec_failed_logins.php';

if($remember) $user->save_identity('permanent');
else $user->save_identity('session');

header('Location: index.php');

exit;

}//2
else if(isset($pending_user)) {
	$_identified_username=$pending_user;
	require $index_dir.'include/code/code_dec_failed_logins.php';
	require $index_dir.'include/code/code_detect8fix_failed_activation.php';
	require $index_dir.'include/page/page_pending_user.php';
	exit;
}
else {
	require $index_dir.'include/code/code_set_submitted_forms_cookie.php';
	$err_msg='You are not authenticated!<br />Check your login information.';
}

}//1

$lockdown_bypass_mode=true;

require $index_dir.'include/page/page_login_form.php';

?>