<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(isset($_POST['username']) and strtolower($_POST['username'])=='admin') {
	$lockdown_threshold=$admin_lockdown_threshold;
	$captcha_threshold=$admin_captcha_threshold;
	$lockdown_period=$admin_lockdown_period;
	$ip_lockdown_threshold=$admin_ip_lockdown_threshold;
	$ip_captcha_threshold=$admin_ip_captcha_threshold;
	$ip_lockdown_period=$admin_ip_lockdown_period;
}

if($lockdown_threshold==-1  and $captcha_threshold==-1) return;
if(isset($captcha_needed) and $lockdown_threshold==-1) return;

$req_time=time();

if($lockdown_threshold==0) {
	$lockdown=$_username;
	$lockdown_duration=$lockdown_period;
	return;
}
else if($captcha_threshold==0) {
	$captcha_needed=true;
	if($lockdown_threshold==-1) return;
}

require_once $index_dir.'include/code/code_db_object.php';

$tmp9=$reg8log_db->quote_smart($_username);

$query="select * from `failed_logins` where `username`=$tmp9 limit 1";

$reg8log_db->query($query);

if(!$reg8log_db->result_num()) return;

$rec=$reg8log_db->fetch_row();

$last_attempt=$rec['last_attempt'];

$attempts = unpack("l10", $rec['attempts']); //it's not 110. it is L10 (lowercase L).

$count=0;
$oldest=$req_time;
foreach($attempts as $value) if(($req_time-$value)<$lockdown_period) {
	$count++;
	if($value<$oldest) $oldest=$value;
}

if($lockdown_threshold!=-1 and $count>=$lockdown_threshold) {
	$lockdown=$_username;
	$lockdown_duration=$oldest+$lockdown_period-$req_time;
	return;
}

if($captcha_threshold!=-1 and $count>=$captcha_threshold) {
	$captcha_needed=true;
	return;
}

?>