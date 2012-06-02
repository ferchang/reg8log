<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(!isset($index_dir)) $index_dir='';

$autos='';
$i=0;
foreach($del as $auto) {
	$autos.="$auto";
	if(++$i==count($del)) break;
	$autos.=", ";
}

$query='delete from `accounts` where `auto` in ('.$autos.") and username!='Admin'";

$reg8log_db->query($query);

$queries_executed=true;

?>
