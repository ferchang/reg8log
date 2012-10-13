<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");



$query='select count(*) as `n` from `account_lockdown_log`';

$reg8log_db->query($query);

$rec=$reg8log_db->fetch_row();

$num=ceil(1/$cleanup_probability);

if(($rec['n']+$num)>$max_security_logs_records) {
	if($rec['n']-$max_security_logs_records>$num) $num=$rec['n']-$max_security_logs_records;
	$query="delete from `account_lockdown_log` order by `last_attempt` asc limit $num";
	$reg8log_db->query($query);
}

?>