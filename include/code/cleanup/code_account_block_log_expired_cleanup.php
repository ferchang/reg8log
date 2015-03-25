<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$expired=$req_time-config::get('account_block_period');

if($keep_expired_block_log_records_for!=-1) $expired-=$keep_expired_block_log_records_for;

$query="delete from `account_block_log` where `first_attempt` < $expired";

$reg8log_db->query($query);

?>