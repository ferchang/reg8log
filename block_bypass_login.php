<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
define('CAN_INCLUDE', true);

$store_request_entropy_probability2=1;

require 'include/common.php';

require ROOT.'include/code/code_encoding8anticache_headers.php';

$block_bypass_mode=true;

if(!config::get('block_bypass_system_enabled')) exit('<h3 align="center">Block-bypass system is disabled by administrator!</h3>');

if(!isset($_GET['key'])) exit('<h3 align="center">Error: key parameter is not set!</h3>');

if(isset($_POST['login2ip'])) $login2ip=true;
else $login2ip=false;

require ROOT.'include/code/code_set_site_salt.php';

if(isset($_POST['username']) and isset($_POST['password'])) {//1

require ROOT.'include/code/code_prevent_repost.php';

$_POST['username']=func::fix_kaaf8yeh($_POST['username']);
	
if(strpos($_POST['password'], "hashed-$site_salt")!==0) $_POST['password']='hashed-'.$site_salt.'-'.hash('sha256', $site_salt.$_POST['password']);

$manual_login=array('username'=>$_POST['username'], 'password'=>$_POST['password']);

if(config::get('block_bypass_system_enabled')==1 and strtolower($_POST['username'])!='admin') exit('<h3 align="center">Currently, block-bypass system is enabled only for the admin account!</h3>');

if(config::get('block_bypass_system_enabled')==2 and strtolower($_POST['username'])=='admin') exit('<h3 align="center">Currently, block-bypass system is disabled for the admin account!</h3>');

$_username=$_POST['username'];
require ROOT.'include/code/code_check_account_block.php';

if(!isset($account_block)) {

	if(!config::get('block_bypass_system_also4ip_block')) func::my_exit('<h3 align=center>'.sprintf(func::tr('account is not blocked msg'), htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8')).'</h3><center><a href="index.php">'.func::tr('Login page').'</a></center>');
	
	$_username=$_POST['username'];
	require ROOT.'include/code/code_check_ip_block.php';
	
	if(!isset($ip_block)) func::my_exit('<h3 align=center>'.sprintf(func::tr('account or ip is not blocked msg'), htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8'), $_SERVER['REMOTE_ADDR']).'</h3><center><a href="index.php">'.func::tr('Login page').'</a></center>');

}

require_once ROOT.'include/code/code_db_object.php';

$_username=$reg8log_db->quote_smart($_POST['username']);
$query="select * from `block_bypass` where `username`=$_username limit 1";

if(!$reg8log_db->result_num($query)) func::my_exit('<h3 align="center">'.func::tr('Error: Block-bypass link not verified!').'</h3>');

$rec=$reg8log_db->fetch_row();
$key=$rec['key'];
$incorrect_logins=$rec['incorrect_logins'];
$block_bypass_record_auto=$rec['auto'];

if($_GET['key']!==$key) func::my_exit('<h3 align="center">'.func::tr('Error: Block-bypass link not verified!').'</h3>');

if(config::get('block_bypass_max_incorrect_logins') and $incorrect_logins>=config::get('block_bypass_max_incorrect_logins')) func::my_exit('<center><h3>'.func::tr('max incorrect logins reached msg').'</h3><br><a href="index.php">'.func::tr('Login page').'</a></center>');

unset($identified_user);
unset($identify_error);

$pass_banned_user=true;
require ROOT.'include/code/code_identify.php';

if(isset($identify_error)) {
	$failure_msg=(config::get('debug_mode'))? $user->err_msg : func::tr('Identification error');
	require ROOT.'include/page/page_failure.php';
	exit;
}

if(isset($identified_user)) {//2

	$_identified_username=$identified_user;

	require ROOT.'include/code/dec/code_dec_incorrect_logins.php';

	require_once ROOT.'include/code/code_save_login.php';

	header('Location: index.php');

	exit;

}//2
else if(isset($pending_user)) {
	$_identified_username=$pending_user;

	require ROOT.'include/code/dec/code_dec_incorrect_logins.php';

	require ROOT.'include/code/code_detect8fix_failed_activation.php';
	require ROOT.'include/page/page_pending_user.php';
	exit;
}
//--------------------
else if(isset($banned_user)) {
	if(isset($manual_login)) require_once ROOT.'include/code/code_save_login.php';
	require ROOT.'include/page/page_banned_user.php';
	exit;
}
//--------------------
else {
	require ROOT.'include/code/code_set_submitted_forms_cookie.php';
	require ROOT.'include/code/code_block_bypass_incorrect_login.php';
	$err_msg=func::tr('Check login information msg');
}

}//1

require ROOT.'include/page/page_login_form.php';

?>