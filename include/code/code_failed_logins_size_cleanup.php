<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(!isset($index_dir)) $index_dir='';

$reg8log_db->query("lock tables `$table_name` write");

$query="select count(*) as `n` from `$table_name` where `username_exists`=0";

$reg8log_db->query($query);

$rec=$reg8log_db->fetch_row();

$num=ceil(1/$cleanup_probability);

if(($rec['n']+$num)<=$max_nonexistent_users_records) return;

if($rec['n']-$max_nonexistent_users_records>$num) $num=$rec['n']-$max_nonexistent_users_records;

$query="delete from `$table_name` where `username_exists`=0 order by `last_attempt` asc limit $num";

$reg8log_db->query($query);

$reg8log_db->query("UNLOCK TABLES");

?>