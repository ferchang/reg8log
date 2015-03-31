<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

foreach($unblock as $item) {
	$query='delete from `ip_incorrect_logins` where ';
	$query.=' `admin`='.$reg8log_db->quote_smart($item['admin']);
	$query.=' and `ip`='.$reg8log_db->quote_smart($item['ip']);
	$query.=' and `timestamp`<='.$reg8log_db->quote_smart($item['last_attempt']);
	$reg8log_db->query($query);
}

$autos='';
$i=0;
foreach($unblock as $item) {
	if(!is_numeric($item['auto'])) exit('error: auto value not numeric!');
	$autos.="{$item['auto']}";
	if(++$i===count($unblock)) break;
	$autos.=", ";
}

$query='update `ip_block_log` set `unblocked`=1 where `auto` in ('.$autos.")";

$reg8log_db->query($query);

$queries_executed=true;

?>
