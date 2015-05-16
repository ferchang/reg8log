<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$query='select count(*) as `n` from `ip_block_log`';

$GLOBALS['reg8log_db']->query($query);

$rec=$GLOBALS['reg8log_db']->fetch_row();

$num=ceil(1/config::get('cleanup_probability'));

if(($rec['n']+$num)>config::get('max_security_logs_records')) {
	if($rec['n']-config::get('max_security_logs_records')>$num) $num=$rec['n']-config::get('max_security_logs_records');
	$query="delete from `ip_block_log` order by `last_attempt` asc limit $num";
	$GLOBALS['reg8log_db']->query($query);
}

?>