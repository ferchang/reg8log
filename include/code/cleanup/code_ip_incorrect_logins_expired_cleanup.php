<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(strtolower($_POST['username'])=='admin') {

	require ROOT.'include/config/config_brute_force_protection.php';
	
	$expired=$req_time-$admin_ip_block_period;

	$query="delete from `ip_incorrect_logins` where `timestamp` < $expired and `admin`=1";

	$reg8log_db->query($query);

	$account_block_threshold=$admin_account_block_threshold;
	$account_captcha_threshold=$admin_account_captcha_threshold;
	$account_block_period=$admin_account_block_period;
	$ip_block_threshold=$admin_ip_block_threshold;
	$ip_captcha_threshold=$admin_ip_captcha_threshold;
	$ip_block_period=$admin_ip_block_period;
	
	return;
	
}

$expired=$req_time-$ip_block_period;

$query="delete from `ip_incorrect_logins` where `timestamp` < $expired and `admin`=0";

$reg8log_db->query($query);

?>