<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(strtolower($_POST['username'])=='admin') require $index_dir.'include/info/info_lockdown.php';

$expired=time()-$ip_lockdown_period;

$query="delete from `correct_logins` where `timestamp` < $expired";

$reg8log_db->query($query);

$query="delete from `incorrect_logins` where `timestamp` < $expired and `admin`=0";

$reg8log_db->query($query);

$expired=time()-$admin_ip_lockdown_period;

$query="delete from `incorrect_logins` where `timestamp` < $expired and `admin`=1";

$reg8log_db->query($query);

if(strtolower($_POST['username'])=='admin') {
	$lockdown_threshold=$admin_lockdown_threshold;
	$captcha_threshold=$admin_captcha_threshold;
	$lockdown_period=$admin_lockdown_period;
	$ip_lockdown_threshold=$admin_ip_lockdown_threshold;
	$ip_captcha_threshold=$admin_ip_captcha_threshold;
	$ip_lockdown_period=$admin_ip_lockdown_period;
}

?>