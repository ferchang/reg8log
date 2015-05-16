<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(isset($identified_user)) $tmp28=$GLOBALS['reg8log_db']->quote_smart($identified_user);
else if(isset($banned_user)) $tmp28=$GLOBALS['reg8log_db']->quote_smart($banned_user);

$query='update `accounts` set ';

switch($log_activity) {
	case 1:
		$query.='`last_login`='.REQUEST_TIME;
	break;
	case 2:
		$query.='`last_activity`='.REQUEST_TIME;
	break;
	case 3:
		$query.='`last_login`='.REQUEST_TIME.', `last_activity`='.REQUEST_TIME;
	break;
}

$query.=' where `username`='.$tmp28.' limit 1';

$GLOBALS['reg8log_db']->query($query);

?>