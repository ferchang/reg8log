<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

if($ip_lockdown_threshold==-1 and $ip_captcha_threshold==-1) return;
if($ip_captcha_threshold==-1 and $dont_block_admin_account==2 and strtolower($manual_identify['username'])==='admin') return;

if(!$ip_lockdown_proportional and (isset($identified_user) or isset($pending_user) or isset($banned_user))) return;

require_once $index_dir.'include/func/func_inet.php';

$ip=$reg8log_db->quote_smart(inet_pton2($_SERVER['REMOTE_ADDR']));

if(isset($identified_user) or isset($pending_user) or isset($banned_user)) {
	$username_hash=$reg8log_db->quote_smart(substr(md5($manual_identify['username'], true), 12));
	$query='replace into `correct_logins` (`ip`, `username_hash`, `timestamp`) values ('.$ip.', '.$username_hash.', '.time().')';
}
else {
	if($count+1>=$ip_lockdown_threshold and ($dont_block_admin_account!=2 or strtolower($manual_identify['username'])!=='admin')) {
		$ip_lockdown=$_SERVER['REMOTE_ADDR'];
		require_once $index_dir.'include/code/code_log_ip_block.php';
	}
	$query='insert into `incorrect_logins` (`ip`, `timestamp`) values ('."$ip, ".time().')';
}

$reg8log_db->query($query);

require_once $index_dir.'include/info/info_cleanup.php';

if(mt_rand(1, floor(1/$cleanup_probability))==1) require $index_dir.'include/code/code_login_attempts_expired_cleanup.php';

if(mt_rand(1, floor(1/$cleanup_probability))==1) require $index_dir.'include/code/code_login_attempts_size_cleanup.php';

?>
