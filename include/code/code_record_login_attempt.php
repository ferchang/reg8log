<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(strtolower($_POST['username'])=='admin') {
	$lockdown_threshold=$admin_lockdown_threshold;
	$captcha_threshold=$admin_captcha_threshold;
	$lockdown_period=$admin_lockdown_period;
	$ip_lockdown_threshold=$admin_ip_lockdown_threshold;
	$ip_captcha_threshold=$admin_ip_captcha_threshold;
	$ip_lockdown_period=$admin_ip_lockdown_period;
	$admin=1;
}
else $admin=0;

if($ip_lockdown_threshold==-1 and $ip_captcha_threshold==-1) return;

if(!$ip_lockdown_proportional and (isset($identified_user) or isset($pending_user) or isset($banned_user))) return;

require_once $index_dir.'include/func/func_inet.php';

$ip=$reg8log_db->quote_smart(inet_pton2($_SERVER['REMOTE_ADDR']));

if(isset($identified_user) or isset($pending_user) or isset($banned_user)) {
	$username_hash=$reg8log_db->quote_smart(substr(md5(strtolower($_POST['username']), true), 12));
	$query='replace into `correct_logins` (`ip`, `username_hash`, `timestamp`) values ('.$ip.', '.$username_hash.', '.time().')';
}
else {
	if($ip_lockdown_threshold!=-1 and $count+1>=$ip_lockdown_threshold) {
		require_once $index_dir.'include/code/code_accomodate_block_disable.php';
		if($block_disable!=1 and $block_disable!=3) $ip_lockdown=$_SERVER['REMOTE_ADDR'];
		else if($ip_captcha_threshold!=-1 and $count+1>=$ip_captcha_threshold) $captcha_needed=true;
		require_once $index_dir.'include/code/code_log_ip_block.php';
	}
	else if($ip_captcha_threshold!=-1 and $count+1>=$ip_captcha_threshold) $captcha_needed=true;
	$query='insert into `incorrect_logins` (`ip`, `timestamp`, `admin`) values ('."$ip, ".time().", $admin)";
}

$reg8log_db->query($query);

require_once $index_dir.'include/info/info_cleanup.php';

if(mt_rand(1, floor(1/$cleanup_probability))==1) require $index_dir.'include/code/code_login_attempts_expired_cleanup.php';

if(mt_rand(1, floor(1/$cleanup_probability))==1) require $index_dir.'include/code/code_login_attempts_size_cleanup.php';

?>
