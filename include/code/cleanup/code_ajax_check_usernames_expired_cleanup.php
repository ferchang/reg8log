<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$expired=REQUEST_TIME-config::get('max_ajax_check_usernames_period');

$query="delete from `ajax_check_usernames` where `timestamp` < $expired";

$GLOBALS['reg8log_db']->query($query);

?>