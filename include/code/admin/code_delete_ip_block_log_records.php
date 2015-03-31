<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$autos='';
$i=0;
foreach($del as $auto) {
	if(!is_numeric($auto)) exit('error: auto value not numeric!');
	$autos.="$auto";
	if(++$i===count($del)) break;
	$autos.=", ";
}

$query='delete from `ip_block_log` where `auto` in ('.$autos.")";

$reg8log_db->query($query);

$queries_executed=true;

?>
