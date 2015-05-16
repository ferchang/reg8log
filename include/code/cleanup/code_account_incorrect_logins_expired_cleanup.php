<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$GLOBALS['reg8log_db']->query("lock tables `$table_name` write");

$expired=REQUEST_TIME-config::get('account_block_period');

$query="delete from `$table_name` where `last_attempt` < $expired and `username`!='Admin'";

$GLOBALS['reg8log_db']->query($query);

$GLOBALS['reg8log_db']->query("UNLOCK TABLES");

?>