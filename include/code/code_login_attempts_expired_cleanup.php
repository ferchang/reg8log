<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(strtolower($_POST['username'])=='admin') require $index_dir.'include/info/info_brute_force_protection.php';

$expired=time()-$ip_block_period;

$query="delete from `ip_correct_logins` where `timestamp` < $expired";

$reg8log_db->query($query);

$query="delete from `ip_incorrect_logins` where `timestamp` < $expired and `admin`=0";

$reg8log_db->query($query);

$expired=time()-$admin_ip_block_period;

$query="delete from `ip_incorrect_logins` where `timestamp` < $expired and `admin`=1";

$reg8log_db->query($query);

if(strtolower($_POST['username'])=='admin') {
	$account_block_threshold=$admin_account_block_threshold;
	$account_captcha_threshold=$admin_account_captcha_threshold;
	$account_block_period=$admin_account_block_period;
	$ip_block_threshold=$admin_ip_block_threshold;
	$ip_captcha_threshold=$admin_ip_captcha_threshold;
	$ip_block_period=$admin_ip_block_period;
}

?>