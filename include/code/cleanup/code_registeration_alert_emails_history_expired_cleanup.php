<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$GLOBALS['reg8log_db']->query('delete from `registeration_alert_emails_history` where `timestamp`<'.(REQUEST_TIME-config::get('max_registeration_alert_emails_period')));

?>