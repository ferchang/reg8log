<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

foreach($unblock as $item) {
	$query='delete from `ip_incorrect_logins` where `ip`=';
	$query.=$reg8log_db->quote_smart($item['ip']);
	$query.=' and `timestamp`<='.$item['last_attempt'];
	$reg8log_db->query($query);
}

$autos='';
$i=0;
foreach($unblock as $item) {
	$autos.="{$item['auto']}";
	if(++$i==count($unblock)) break;
	$autos.=", ";
}

$query='update `ip_block_log` set `unblocked`=1 where `auto` in ('.$autos.")";

$reg8log_db->query($query);

$queries_executed=true;

?>
