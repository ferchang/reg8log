<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$expired=REQUEST_TIME-$verification_time;

$GLOBALS['reg8log_db']->query("delete from `email_change` where `timestamp` < $expired");

?>