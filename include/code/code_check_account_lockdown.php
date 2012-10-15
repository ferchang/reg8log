<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

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

$attempts = unpack("l10", $rec['attempts']);

$count=0;
$oldest=$req_time;
foreach($attempts as $value) if(($req_time-$value)<$lockdown_period) {
	$count++;
	if($value<$oldest) $oldest=$value;
}

if($lockdown_threshold!=-1 and $count>=$lockdown_threshold and (!$dont_block_admin_account or strtolower($manual_identify['username'])!=='admin')) {
	$lockdown=$_username;
	$lockdown_duration=$oldest+$lockdown_period-$req_time;
	return;
}

if($captcha_threshold!=-1 and $count>=$captcha_threshold) {
	$captcha_needed=true;
	return;
}

?>