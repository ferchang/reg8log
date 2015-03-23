<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

if($account_block_threshold==-1  and $account_captcha_threshold==-1) return;

if(!isset($incorrect_logins_auto, $_COOKIE['reg8log_account_incorrect_logins'])) return;

$autos=explode(',', $_COOKIE['reg8log_account_incorrect_logins']);

$matches=null;
$tmp10=$autos;
for($i=0; $i<count($tmp10); $i+=2) if($tmp10[$i]===$incorrect_logins_auto) {
	$matches[]=$tmp10[$i+1];
	unset($autos[$i], $autos[$i+1]);
}

if(!$matches) return;

setcookie('reg8log_account_incorrect_logins', implode(',', $autos), 0, '/', null, HTTPS, true);

require_once ROOT.'include/code/code_db_object.php';

require_once ROOT.'include/code/code_fetch_site_vars.php';

$lock_name=$reg8log_db->quote_smart('reg8log--incorrect_login-'.$_POST['username']."--$site_key");
$reg8log_db->query("select get_lock($lock_name, -1)");

$query="select * from `account_incorrect_logins` where `auto`=$incorrect_logins_auto limit 1";

$reg8log_db->query($query);

if(!$reg8log_db->result_num()) return;

$tmp10=$reg8log_db->fetch_row();// note: don't use $rec instead of $tmp10

$attempts=unpack("l10", $tmp10['attempts']);

if($matches) foreach($attempts as $key=>$value) if(in_array($value, $matches)) $attempts[$key]=0;

$count=0;
foreach($attempts as $value) if(($req_time-$value)<$account_block_period) $count++;

if(!$count) {
	$query="delete from `account_incorrect_logins` where `auto`=$incorrect_logins_auto limit 1";
	$reg8log_db->query($query);
	return;
}

if(!$matches) return;

$last_attempt=max($attempts);

$attempts=$reg8log_db->quote_smart(pack('l10', $attempts[1], $attempts[2], $attempts[3], $attempts[4], $attempts[5], $attempts[6], $attempts[7], $attempts[8], $attempts[9], $attempts[10]));

$query="update `account_incorrect_logins` set `attempts`=$attempts, `last_attempt`=$last_attempt where `auto`=$incorrect_logins_auto limit 1";

$reg8log_db->query($query);

?>