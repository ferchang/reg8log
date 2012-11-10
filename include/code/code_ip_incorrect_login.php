<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(strtolower($_POST['username'])=='admin') {
	$account_block_threshold=$admin_account_block_threshold;
	$account_captcha_threshold=$admin_account_captcha_threshold;
	$account_block_period=$admin_account_block_period;
	$ip_block_threshold=$admin_ip_block_threshold;
	$ip_captcha_threshold=$admin_ip_captcha_threshold;
	$ip_block_period=$admin_ip_block_period;
	$admin=1;
}
else $admin=0;

if($ip_block_threshold==-1 and $ip_captcha_threshold==-1) return;

if(isset($identified_user) or isset($pending_user) or isset($banned_user)) return;

require_once $index_dir.'include/func/func_inet.php';

$ip=$reg8log_db->quote_smart(inet_pton2($_SERVER['REMOTE_ADDR']));

if($ip_block_threshold!=-1 and $ip_incorrect_count+1>=$ip_block_threshold) {
	$_username2=$_POST['username'];
	require_once $index_dir.'include/code/code_accomodate_block_disable.php';
	if($block_disable!=1 and $block_disable!=3) $ip_block=$_SERVER['REMOTE_ADDR'];
	else if($ip_captcha_threshold!=-1 and $ip_incorrect_count+1>=$ip_captcha_threshold) $captcha_needed=true;
	require_once $index_dir.'include/code/code_log_ip_block.php';
}
else if($ip_captcha_threshold!=-1 and $ip_incorrect_count+1>=$ip_captcha_threshold) $captcha_needed=true;

if($username_exists) $account_auto=$user->user_info['auto'];
else $account_auto=0;

$query='insert into `ip_incorrect_logins` (`ip`, `account_auto`, `timestamp`, `admin`) values '."($ip, $account_auto, $req_time, $admin)";

$reg8log_db->query($query);

$insert_id2=mysql_insert_id();

$cookie_capacity=30;
if(!isset($_COOKIE['reg8log_ip_incorrect_logins'])) $cookie_contents=$insert_id2;
else {
	$cookie_contents=$_COOKIE['reg8log_ip_incorrect_logins'].','.$insert_id2;
	$cookie_contents=implode(',', array_slice(explode(',', $cookie_contents), -1*$cookie_capacity));
}
setcookie('reg8log_ip_incorrect_logins', $cookie_contents, 0, '/', null, $https, true);

require_once $index_dir.'include/config/config_cleanup.php';

if(mt_rand(1, floor(1/$cleanup_probability))==1) require $index_dir.'include/code/code_ip_incorrect_logins_expired_cleanup.php';

if(mt_rand(1, floor(1/$cleanup_probability))==1) require $index_dir.'include/code/code_ip_incorrect_logins_size_cleanup.php';

?>
