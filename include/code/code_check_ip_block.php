<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

if((isset($_POST['username']) and strtolower($_POST['username'])=='admin') or isset($is_admin)) {
	$account_block_threshold=$admin_account_block_threshold;
	$account_captcha_threshold=$admin_account_captcha_threshold;
	$account_block_period=$admin_account_block_period;
	$ip_block_threshold=$admin_ip_block_threshold;
	$ip_captcha_threshold=$admin_ip_captcha_threshold;
	$ip_block_period=$admin_ip_block_period;
	$_admin=1;
}
else $_admin=0;

if($ip_block_threshold==-1  and $ip_captcha_threshold==-1) return;
if(isset($captcha_needed) and $ip_block_threshold==-1) return;

if(!isset($site_key)) require ROOT.'include/code/code_fetch_site_vars.php';
$ip_login_attempt_lock="'".'reg8log--ip_login_attempt-'.$_SERVER['REMOTE_ADDR']."--$site_key"."'";
$reg8log_db->query("select get_lock($ip_login_attempt_lock, -1)");

if(!isset($last_protection)) {
	require_once ROOT.'include/code/code_db_object.php';
	$tmp30=$reg8log_db->quote_smart($_username);
	$query="select * from `accounts` where `username`=$tmp30 limit 1";
	$reg8log_db->query($query);
	$rec=$reg8log_db->fetch_row();
	$block_disable=$rec['block_disable'];
	$last_protection=$rec['last_protection'];
}

if($ip_block_threshold==0) {
	$ip_block=true;
	return;
}

if($ip_captcha_threshold==0) {
	$captcha_needed=true;
	if($ip_block_threshold==-1) return;
}

require_once ROOT.'include/code/code_db_object.php';

require_once ROOT.'include/func/func_inet.php';

$ip=$reg8log_db->quote_smart(inet_pton2($_SERVER['REMOTE_ADDR']));

$query="select count(*) as `n` from `ip_incorrect_logins` where `admin`=$_admin and `ip`=$ip and `timestamp`>=".($req_time-$ip_block_period);
$reg8log_db->query($query);
$rec=$reg8log_db->fetch_row();
$count=$rec['n'];
$ip_incorrect_count=$count;

if($ip_block_threshold!=-1 and $count>=$ip_block_threshold) {
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

if($ip_captcha_threshold!=-1 and $count>=$ip_captcha_threshold) $captcha_needed=true;

?>