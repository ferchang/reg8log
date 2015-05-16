<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$GLOBALS['reg8log_db']->query("lock tables `$table_name` write");

$query="select count(*) as `n` from `$table_name` where `username_exists`=0";

$GLOBALS['reg8log_db']->query($query);

$rec=$GLOBALS['reg8log_db']->fetch_row();

$num=ceil(1/config::get('cleanup_probability'));

if(($rec['n']+$num)<=config::get('max_nonexistent_users_records')) return;

if($rec['n']-config::get('max_nonexistent_users_records')>$num) $num=$rec['n']-config::get('max_nonexistent_users_records');

$query="delete from `$table_name` where `username_exists`=0 order by `last_attempt` asc limit $num";

$GLOBALS['reg8log_db']->query($query);

$GLOBALS['reg8log_db']->query("UNLOCK TABLES");

?>