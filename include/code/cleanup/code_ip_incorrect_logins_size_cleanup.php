<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$query='select count(*) as `n` from `ip_incorrect_logins`';

$GLOBALS['reg8log_db']->query($query);

$rec=$GLOBALS['reg8log_db']->fetch_row();

$num=ceil(1/config::get('cleanup_probability'));

if(($rec['n']+$num)<=config::get('max_ip_incorrect_login_records')) return;

if($rec['n']-config::get('max_ip_incorrect_login_records')>$num) $num=$rec['n']-config::get('max_ip_incorrect_login_records');

$query="delete from `ip_incorrect_logins` order by `timestamp` asc limit $num";

$GLOBALS['reg8log_db']->query($query);

?>