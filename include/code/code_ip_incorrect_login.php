<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(strtolower($_POST['username'])==='admin') {
	config::set('account_block_threshold', config::get('admin_account_block_threshold'));
	config::set('account_captcha_threshold', config::get('admin_account_captcha_threshold'));
	config::set('account_block_period', config::get('admin_account_block_period'));
	config::set('ip_block_threshold', config::get('admin_ip_block_threshold'));
	config::set('ip_captcha_threshold', config::get('admin_ip_captcha_threshold'));
	config::set('ip_block_period', config::get('admin_ip_block_period'));
	$admin=1;
}
else $admin=0;

if(config::get('ip_block_threshold')===-1 and config::get('ip_captcha_threshold')===-1) return;

if(isset($identified_user) or isset($pending_user) or isset($banned_user)) return;

$ip=$reg8log_db->quote_smart(func::inet_pton2($_SERVER['REMOTE_ADDR']));

if(config::get('ip_block_threshold')!==-1 and $ip_incorrect_count+1>=config::get('ip_block_threshold')) {
	$_username2=$_POST['username'];
	require_once ROOT.'include/code/code_accomodate_block_disable.php';
	if($block_disable!=1 and $block_disable!=3) $ip_block=$_SERVER['REMOTE_ADDR'];
	else if(config::get('ip_captcha_threshold')!==-1 and $ip_incorrect_count+1>=config::get('ip_captcha_threshold')) $captcha_needed=true;
	if($ip_incorrect_count<config::get('ip_block_threshold')) if(isset($ip_block) or strtolower($_POST['username'])!=='admin') require_once ROOT.'include/code/log/code_log_ip_block.php';
}
else if(config::get('ip_captcha_threshold')!==-1 and $ip_incorrect_count+1>=config::get('ip_captcha_threshold')) $captcha_needed=true;

if($username_exists) $account_auto=$user->user_info['auto'];
else $account_auto=0;

if(!isset($is_pending_account)) $is_pending_account=0;

$query='insert into `ip_incorrect_logins` (`ip`, `account_auto`, `timestamp`, `admin`, `pending_account`) values '."($ip, $account_auto, ".REQUEST_TIME.", $admin, $is_pending_account)";

$reg8log_db->query($query);

$insert_id2=mysql_insert_id();

$cookie_capacity=30;
if(!isset($_COOKIE['reg8log_ip_incorrect_logins'])) $cookie_contents=$insert_id2;
else {
	$cookie_contents=$_COOKIE['reg8log_ip_incorrect_logins'].','.$insert_id2;
	$cookie_contents=implode(',', array_slice(explode(',', $cookie_contents), -1*$cookie_capacity));
}
setcookie('reg8log_ip_incorrect_logins', $cookie_contents, 0, '/', null, HTTPS, true);

if(mt_rand(1, floor(1/config::get('cleanup_probability')))===1) require ROOT.'include/code/cleanup/code_ip_incorrect_logins_expired_cleanup.php';

if(mt_rand(1, floor(1/config::get('cleanup_probability')))===1) require ROOT.'include/code/cleanup/code_ip_incorrect_logins_size_cleanup.php';

?>
