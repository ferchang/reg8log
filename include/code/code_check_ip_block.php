<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

if((isset($_POST['username']) and strtolower($_POST['username'])==='admin') or isset($is_admin)) {
	config::set('account_block_threshold', config::get('admin_account_block_threshold'));
	config::set('account_captcha_threshold', config::get('admin_account_captcha_threshold'));
	config::set('account_block_period', config::get('admin_account_block_period'));
	config::set('ip_block_threshold', config::get('admin_ip_block_threshold'));
	config::set('ip_captcha_threshold', config::get('admin_ip_captcha_threshold'));
	config::set('ip_block_period', config::get('admin_ip_block_period'));
	$_admin=1;
}
else $_admin=0;

if(config::get('ip_block_threshold')===-1  and config::get('ip_captcha_threshold')===-1) return;
if(isset($captcha_needed) and config::get('ip_block_threshold')===-1) return;

$ip_login_attempt_lock="'".'reg8log--ip_login_attempt-'.$_SERVER['REMOTE_ADDR'].'--'.SITE_KEY."'";
$reg8log_db->query("select get_lock($ip_login_attempt_lock, -1)");

if(!isset($last_protection)) {
	$tmp30=$reg8log_db->quote_smart($_username);
	$query="select * from `accounts` where `username`=$tmp30 limit 1";
	$reg8log_db->query($query);
	$rec=$reg8log_db->fetch_row();
	$block_disable=$rec['block_disable'];
	$last_protection=$rec['last_protection'];
}

if(config::get('ip_block_threshold')===0) {
	$ip_block=true;
	return;
}

if(config::get('ip_captcha_threshold')===0) {
	$captcha_needed=true;
	if(config::get('ip_block_threshold')===-1) return;
}

$ip=$reg8log_db->quote_smart(func::inet_pton2($_SERVER['REMOTE_ADDR']));

$query="select count(*) as `n` from `ip_incorrect_logins` where `admin`=$_admin and `ip`=$ip and `timestamp`>=".($req_time-config::get('ip_block_period'));
$reg8log_db->query($query);
$rec=$reg8log_db->fetch_row();
$count=$rec['n'];
$ip_incorrect_count=$count;

if(config::get('ip_block_threshold')!==-1 and $count>=config::get('ip_block_threshold')) {
	$_username2=$_username;
	require_once ROOT.'include/code/code_accomodate_block_disable.php';
	if($block_disable!=1 and $block_disable!=3) {
		$ip_block=$_SERVER['REMOTE_ADDR'];
		if(isset($set_last_attempt)) {
			$query="select * from `ip_incorrect_logins` where `admin`=$_admin and `ip`=$ip order by `timestamp` desc limit 1";
			$reg8log_db->query($query);
			$rec=$reg8log_db->fetch_row();
			$last_attempt=$rec['timestamp'];
		}
		return;
	}
}

if(config::get('ip_captcha_threshold')!==-1 and $count>=config::get('ip_captcha_threshold')) $captcha_needed=true;

?>