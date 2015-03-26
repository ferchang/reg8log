<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
define('CAN_INCLUDE', true);

require 'include/common.php';

require ROOT.'include/code/code_encoding8anticache_headers.php';

require ROOT.'include/code/code_prevent_repost.php';

require ROOT.'include/config/config_brute_force_protection.php';

if(!config::get('block_bypass_system_enabled')) exit('<h3 align="center">Block-bypass system is disabled by administrator!</h3>');

if(!isset($_GET['username'])) exit('<h3 align="center">Error: username parameter is not set</h3>');

if(config::get('block_bypass_system_enabled')==1 and strtolower($_GET['username'])!='admin') exit('<h3 align="center">Currently, block-bypass system is enabled only for the admin account!</h3>');

if(config::get('block_bypass_system_enabled')==2 and strtolower($_GET['username'])=='admin') exit('<h3 align="center">Currently, block-bypass system is disabled for the admin account!</h3>');

if(strtolower($_GET['username'])=='admin') $is_admin=true;

$_username=$_GET['username'];
require ROOT.'include/code/code_check_account_block.php';

//echo 2222;

if(!isset($account_block)) {

	if(!config::get('block_bypass_system_also4ip_block')) func::my_exit('<h3 align=center>'.sprintf(func::tr('account is not blocked msg'), htmlspecialchars($_GET['username'], ENT_QUOTES, 'UTF-8')).'</h3><center><a href="index.php">'.func::tr('Login page').'</a></center>');
	
	$_username=$_GET['username'];
	$set_last_attempt=true;
	require ROOT.'include/code/code_check_ip_block.php';
	
	if(!isset($ip_block)) func::my_exit('<h3 align=center>'.sprintf(func::tr('account or ip is not blocked msg'), htmlspecialchars($_GET['username'], ENT_QUOTES, 'UTF-8'), $_SERVER['REMOTE_ADDR']).'</h3><center><a href="index.php">'.func::tr('Login page').'</a></center>');
	
}

require_once ROOT.'include/code/code_db_object.php';

require_once ROOT.'include/code/code_fetch_site_vars.php';

$lock_name=$reg8log_db->quote_smart('reg8log--block_bypass-'.$_GET['username']."--$site_key");
$reg8log_db->query("select get_lock($lock_name, -1)");

$_username=$reg8log_db->quote_smart($_GET['username']);
$query='select * from `block_bypass` where `username`='.$_username.' limit 1';
if($reg8log_db->result_num($query)) {
  $rec=$reg8log_db->fetch_row();
  $num_requests=$rec['num_requests'];
  $emails_sent=$rec['emails_sent'];
  $key=$rec['key'];
  if($rec['last_attempt']!=$last_attempt) $bypass_record_expired=true;
  else if($num_requests>=3) $captcha_needed=true;
}
else $emails_sent=$num_requests=-1;

if(isset($captcha_needed)) {
	require ROOT.'include/code/sess/code_sess_start.php';
	$captcha_verified=isset($_SESSION['reg8log']['captcha_verified']);
}

do {//goto statement not supported in PHP < 5.3; so i use do ... while(false) + break in this specific scenario instead.

if(!isset($_POST['email']))  break;

require ROOT.'include/code/code_prevent_xsrf.php';

if($_POST['email']==='') $err_msgs[]=func::tr('Email field is empty!');
else {
	$email_re='/^[a-z0-9_\-+\.]+@([a-z0-9\-+]+\.)+[a-z]{2,5}$/i';
	if(!preg_match($email_re, $_POST['email'])) $err_msgs[]=func::tr('Email format is invalid!');
}

if(isset($captcha_needed) and !$captcha_verified) require ROOT.'include/code/code_verify_captcha.php';

if(isset($err_msgs)) break;

$query1='select * from `accounts` where `username`='.$_username.' limit 1';

require ROOT.'include/config/config_register.php';

$expired1=$req_time-config::get('email_verification_time');
$expired2=$req_time-config::get('admin_confirmation_time');

$query2='select * from `pending_accounts` where `username`='.$_username." and (`email_verification_key`='' or `email_verified`=1 or `timestamp`>=".$expired1.') and (`admin_confirmed`=1 or `timestamp`>='.$expired2.') limit 1';

if($reg8log_db->result_num($query1) or $reg8log_db->result_num($query2)) {
  $username_exists=1;
  $rec=$reg8log_db->fetch_row();
  $email=$rec['email'];
}
else {
  $email=false;
  $username_exists=0;
}

$new_key=func::random_string(22);

$tmp22=$emails_sent;
if($_POST['email']===$email and (config::get('max_block_bypass_emails')==-1 or $emails_sent<config::get('max_block_bypass_emails'))) if($emails_sent<255) $tmp22++;

if($num_requests<255) $num_requests++;

if($num_requests>0) {
  if(!isset($bypass_record_expired))
    $query='update `block_bypass` set `username_exists`='.$username_exists.', `num_requests`='.$num_requests.', `emails_sent`='.$tmp22.' where `username`='.$_username.' limit 1';
  else {
    $key=$new_key;
    $query='update `block_bypass` set `username_exists`='.$username_exists.", `num_requests`=1, `emails_sent`=0, `key`='".$key."', `last_attempt`=".$last_attempt.', `incorrect_logins`=0 where `username`='.$_username.' limit 1';
  }
}
else {
  $key=$new_key;
  $insert=true;
  $query='insert into `block_bypass` (`username`, `username_exists`, `num_requests`, `emails_sent`, `key`, `last_attempt`, `incorrect_logins`) values'."($_username, $username_exists, 1, 0, '$key', $last_attempt, 0)";
}

$reg8log_db->query($query);

if($_POST['email']===$email and (config::get('max_block_bypass_emails')==-1 or $emails_sent<config::get('max_block_bypass_emails'))) require ROOT.'include/code/email/code_email_bypass_link.php';

if(isset($captcha_needed)) unset($_SESSION['reg8log']['captcha_verified']);

require ROOT.'include/code/code_set_submitted_forms_cookie.php';

$success_msg='<h3>'.sprintf(func::tr('block-bypass email sent msg'), htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8'), htmlspecialchars($_GET['username'], ENT_QUOTES, 'UTF-8'));
if(config::get('max_block_bypass_emails')!=-1) $success_msg.=sprintf(func::tr('max block-bypass emails msg'), config::get('max_block_bypass_emails'));
$success_msg.='.</h3>';
$no_specialchars=true;
require ROOT.'include/page/page_success.php';

require ROOT.'include/config/config_cleanup.php';

if(isset($insert)) {

	if(mt_rand(1, floor(1/config::get('cleanup_probability')))==1) {
		$table_name='block_bypass';
		require ROOT.'include/code/cleanup/code_account_incorrect_logins_expired_cleanup.php';
	}

	if(mt_rand(1, floor(1/config::get('cleanup_probability')))==1) {
		$table_name='block_bypass';
		require ROOT.'include/code/cleanup/code_account_incorrect_logins_size_cleanup.php';
	}

}

exit;

} while(false);

require ROOT.'include/page/page_block_bypass_request_form.php';

?>
