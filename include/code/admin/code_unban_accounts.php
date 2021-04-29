<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$autos='';
$i=0;
foreach($unban as $username) {
	
	$lock_name=$GLOBALS['reg8log_db']->quote_smart('reg8log--ban-'.strtolower($username).'--'.SITE_KEY);
	$GLOBALS['reg8log_db']->query("select get_lock($lock_name, -1)");

	$username=$GLOBALS['reg8log_db']->quote_smart($username);

	$query='update `accounts` set `banned`=0 where `username`='.$username.' limit 1';

	$GLOBALS['reg8log_db']->query($query);

	$query='delete from `ban_info` where `username`='.$username.' limit 1';

	$GLOBALS['reg8log_db']->query($query);

	$GLOBALS['reg8log_db']->query("select release_lock($lock_name)");

}

$queries_executed=true;

?>
