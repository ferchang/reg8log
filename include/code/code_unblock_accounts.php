<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");



$autos='';
$i=0;
foreach($unblock as $auto) {
	$autos.="$auto";
	if(++$i==count($unblock)) break;
	$autos.=", ";
}

$query='delete from `account_incorrect_logins` where `auto` in ('.$autos.")";

$reg8log_db->query($query);

$query='update `account_block_log` set `unblocked`=1 where `ext_auto` in ('.$autos.")";

$reg8log_db->query($query);

$queries_executed=true;

?>
