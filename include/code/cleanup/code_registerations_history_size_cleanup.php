<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$query='select count(*) as `n` from `registerations_history`';

$reg8log_db->query($query);

$tmp41=$reg8log_db->fetch_row();

$num=ceil(1/config::get('cleanup_probability'));

if(($tmp41['n']+$num)<=config::get('max_registerations_history_records')) return;

if($tmp41['n']-config::get('max_registerations_history_records')>$num) $num=$tmp41['n']-config::get('max_registerations_history_records');

$query="delete from `registerations_history` order by `timestamp` asc limit $num";

$reg8log_db->query($query);

?>