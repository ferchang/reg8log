<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(!isset($index_dir)) $index_dir='';

$expired=time()-$lockdown_period;

if($keep_expired_block_log_records_for!=-1) $expired-=$keep_expired_block_log_records_for;

$query="delete from `account_lockdown_log` where `last_attempt` < $expired";

$reg8log_db->query($query);

?>