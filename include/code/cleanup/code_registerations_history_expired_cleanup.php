<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$reg8log_db->query('delete from `registerations_history` where `timestamp`<'.(REQUEST_TIME-config::get('registerations_alert_threshold_period')));

?>