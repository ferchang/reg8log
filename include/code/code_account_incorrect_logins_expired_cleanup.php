<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

$reg8log_db->query("lock tables `$table_name` write");

$expired=time()-$account_block_period;

$query="delete from `$table_name` where `last_attempt` < $expired and `username`!='admin'";

$reg8log_db->query($query);

$reg8log_db->query("UNLOCK TABLES");

?>