<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$query='update `accounts` set `email`='.$GLOBALS['reg8log_db']->quote_smart($_POST['newemail']).' where `username`='.$GLOBALS['reg8log_db']->quote_smart($identified_user).' limit 1';

$GLOBALS['reg8log_db']->query($query);

?>
