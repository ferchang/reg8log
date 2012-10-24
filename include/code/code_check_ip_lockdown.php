<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

if((isset($_POST['username']) and strtolower($_POST['username'])=='admin') or isset($is_admin)) {
	$lockdown_threshold=$admin_lockdown_threshold;
	$captcha_threshold=$admin_captcha_threshold;
	$lockdown_period=$admin_lockdown_period;
	$ip_lockdown_threshold=$admin_ip_lockdown_threshold;
	$ip_captcha_threshold=$admin_ip_captcha_threshold;
	$ip_lockdown_period=$admin_ip_lockdown_period;
}

if($ip_lockdown_threshold==-1  and $ip_captcha_threshold==-1) return;
if(isset($captcha_needed) and $ip_lockdown_threshold==-1) return;

require_once $index_dir.'include/code/code_db_object.php';
$tmp30=$reg8log_db->quote_smart($_username);
$query="select * from `accounts` where `username`=$tmp30 limit 1";
$reg8log_db->query($query);
$rec=$reg8log_db->fetch_row();
$block_disable=$rec['block_disable'];
$last_protection=$rec['last_protection'];

if($ip_lockdown_threshold==0) {
	$ip_lockdown=true;
	return;
}

if($ip_captcha_threshold==0) {
	$captcha_needed=true;
	if($ip_lockdown_threshold==-1) return;
}

require_once $index_dir.'include/code/code_db_object.php';

require_once $index_dir.'include/func/func_inet.php';

$ip=$reg8log_db->quote_smart(inet_pton2($_SERVER['REMOTE_ADDR']));

if($ip_lockdown_proportional) {
	$query='select count(*) as `n` from `correct_logins` where `ip`='.$ip.' and `timestamp`>='.(time()-$ip_lockdown_period);
	$reg8log_db->query($query);
	$rec=$reg8log_db->fetch_row();
	$correct=$rec['n'];
	//-------------
	$query='select count(*) as `n` from `incorrect_logins` where `ip`='.$ip.' and `timestamp`>='.(time()-$ip_lockdown_period);
	$reg8log_db->query($query);
	$rec=$reg8log_db->fetch_row();
	$incorrect=$rec['n'];
	if($correct==0) $correct=1;
	$count=$incorrect/$correct;
}
else {
	$query='select count(*) as `n` from `incorrect_logins` where `ip`='.$ip.' and `timestamp`>='.(time()-$ip_lockdown_period);
	$reg8log_db->query($query);
	$rec=$reg8log_db->fetch_row();
	$count=$rec['n'];
}

if($ip_lockdown_threshold!=-1 and $count>=$ip_lockdown_threshold) {
	require_once $index_dir.'include/code/code_accomodate_block_disable.php';
	if($block_disable!=1 and $block_disable!=3) {
		$ip_lockdown=$_SERVER['REMOTE_ADDR'];
		if(isset($set_last_attempt)) {
			$query='select * from `incorrect_logins` where `ip`='.$ip.' order by `timestamp` desc limit 1';
			$reg8log_db->query($query);
			$rec=$reg8log_db->fetch_row();
			$last_attempt=$rec['timestamp'];
		}
		return;
	}
}

if($ip_captcha_threshold!=-1 and $count>=$ip_captcha_threshold) $captcha_needed=true;

?>