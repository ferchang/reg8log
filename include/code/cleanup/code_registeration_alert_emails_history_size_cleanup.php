<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$query='select count(*) as `n` from `registeration_alert_emails_history`';

$reg8log_db->query($query);

$tmp40=$reg8log_db->fetch_row();

$num=ceil(1/config::get('cleanup_probability'));

if(($tmp40['n']+$num)<=config::get('max_registeration_alert_emails_history_records')) return;

if($tmp40['n']-config::get('max_registeration_alert_emails_history_records')>$num) $num=$tmp40['n']-config::get('max_registeration_alert_emails_history_records');

$query="delete from `registeration_alert_emails_history` order by `timestamp` asc limit $num";

$reg8log_db->query($query);

?>