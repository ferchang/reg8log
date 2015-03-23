<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$query='select count(*) as `n` from `ip_incorrect_logins_decs`';

$reg8log_db->query($query);

$rec=$reg8log_db->fetch_row();

$num=ceil(1/$cleanup_probability);

if(($rec['n']+$num)<=$max_ip_incorrect_logins_decs_records) return;

if($rec['n']-$max_ip_incorrect_logins_decs_records>$num) $num=$rec['n']-$max_ip_incorrect_logins_decs_records;

$query="delete from `ip_incorrect_logins_decs` order by `timestamp` asc limit $num";

$reg8log_db->query($query);

?>