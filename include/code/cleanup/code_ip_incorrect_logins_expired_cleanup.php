<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(strtolower($_POST['username'])=='admin') {

	require ROOT.'include/config/config_brute_force_protection.php';
	
	$expired=$req_time-config::get('admin_ip_block_period');

	$query="delete from `ip_incorrect_logins` where `timestamp` < $expired and `admin`=1";

	$reg8log_db->query($query);

	config::set('account_block_threshold', config::get('admin_account_block_threshold'));
	config::set('account_captcha_threshold', config::get('admin_account_captcha_threshold'));
	config::set('account_block_period', config::get('admin_account_block_period'));
	config::set('ip_block_threshold', config::get('admin_ip_block_threshold'));
	config::set('ip_captcha_threshold', config::get('admin_ip_captcha_threshold'));
	config::set('ip_block_period', config::get('admin_ip_block_period'));
	
	return;
	
}

$expired=$req_time-config::get('ip_block_period');

$query="delete from `ip_incorrect_logins` where `timestamp` < $expired and `admin`=0";

$reg8log_db->query($query);

?>