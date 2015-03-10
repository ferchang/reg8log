<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$query='select count(*) as `n` from `block_alert_emails_history`';

$reg8log_db->query($query);

$tmp39=$reg8log_db->fetch_row();

$num=ceil(1/$cleanup_probability);

if(($tmp39['n']+$num)<=$max_block_alert_emails_history_records) return;

if($tmp39['n']-$max_block_alert_emails_history_records>$num) $num=$tmp39['n']-$max_block_alert_emails_history_records;

$query="delete from `block_alert_emails_history` order by `timestamp` asc limit $num";

$reg8log_db->query($query);

?>