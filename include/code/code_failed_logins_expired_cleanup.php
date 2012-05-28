<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(!isset($index_dir)) $index_dir='';

$reg8log_db->query("lock tables `$table_name` write");

$expired=time()-$lockdown_period;

$query="delete from `$table_name` where `last_attempt` < $expired";

$reg8log_db->query($query);

$reg8log_db->query("UNLOCK TABLES");

?>