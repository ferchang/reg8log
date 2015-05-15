<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$expired=REQUEST_TIME-config::get('ip_block_period');

if(config::get('keep_expired_block_log_records_for')!==-1) $expired-=config::get('keep_expired_block_log_records_for');

$query="delete from `ip_block_log` where `last_attempt` < $expired";

$reg8log_db->query($query);

?>