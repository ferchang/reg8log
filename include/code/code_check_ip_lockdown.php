<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");



if($ip_lockdown_threshold==-1  and $ip_captcha_threshold==-1) return;
if(isset($captcha_needed) and $ip_lockdown_threshold==-1) return;

if($ip_lockdown_threshold==0) {
	$ip_lockdown=true;
	return;
}

if($ip_captcha_threshold==0) {
	$captcha_needed=true;
	if($ip_lockdown_threshold==-1) return;
}

require_once $index_dir.'include/code/code_db_object.php';

require_once $index_dir.'include/func/func_inet_pton.php';

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
	$ip_lockdown=$_SERVER['REMOTE_ADDR'];
	return;
}

if($ip_captcha_threshold!=-1 and $count>=$ip_captcha_threshold) {
	$captcha_needed=true;
	return;
}

?>