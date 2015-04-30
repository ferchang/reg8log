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
}

if(config::get('account_block_threshold')===-1  and config::get('account_captcha_threshold')===-1) return;
if(isset($captcha_needed) and config::get('account_block_threshold')===-1) return;

$account_login_attempt_lock=$reg8log_db->quote_smart('reg8log--account_login_attempt-'.strtolower($_username).'--'.SITE_KEY);
$reg8log_db->query("select get_lock($account_login_attempt_lock, -1)");

if(!isset($last_protection)) {
	$tmp9=$reg8log_db->quote_smart($_username);
	$query="select * from `accounts` where `username`=$tmp9 limit 1";
	$reg8log_db->query($query);
	$rec=$reg8log_db->fetch_row();
	$block_disable=$rec['block_disable'];
	$last_protection=$rec['last_protection'];
}

if(config::get('account_block_threshold')===0) {
	$account_block=$_username;
	$block_duration=config::get('account_block_period');
	return;
}
else if(config::get('account_captcha_threshold')===0) {
	$captcha_needed=true;
	if(config::get('account_block_threshold')===-1) return;
}

if(!isset($tmp9)) $tmp9=$reg8log_db->quote_smart($_username);
$query="select * from `account_incorrect_logins` where `username`=$tmp9 limit 1";

if(!$reg8log_db->result_num($query)) return;

$rec=$reg8log_db->fetch_row();

$incorrect_logins_auto=$rec['auto'];

$last_attempt=$rec['last_attempt'];

$attempts = unpack("l10", $rec['attempts']); //it's not 110. it is L10 (lowercase L).

$count=0;
$oldest=$req_time;
foreach($attempts as $value) if(($req_time-$value)<config::get('account_block_period')) {
	$count++;
	if($value<$oldest) $oldest=$value;
}

if(config::get('account_block_threshold')!==-1 and $count>=config::get('account_block_threshold')) {
	$_username2=$_username;
	require_once ROOT.'include/code/code_accomodate_block_disable.php';
	if($block_disable!=2 and $block_disable!=3) {
		$account_block=$_username;
		$block_duration=$oldest+config::get('account_block_period')-$req_time;
		return;
	}
}

if(config::get('account_captcha_threshold')!==-1 and $count>=config::get('account_captcha_threshold')) $captcha_needed=true;

?>