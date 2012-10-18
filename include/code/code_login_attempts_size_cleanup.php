<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

$query='select count(*) as `n` from `correct_logins`';

$reg8log_db->query($query);

$rec=$reg8log_db->fetch_row();

$num=ceil(1/$cleanup_probability);

if(($rec['n']+$num)>$max_login_attempt_records) {
	if($rec['n']-$max_login_attempt_records>$num) $num=$rec['n']-$max_login_attempt_records;
	$query="delete from `correct_logins` order by `timestamp` asc limit $num";
	$reg8log_db->query($query);
}

//------------------------------------

$query='select count(*) as `n` from `incorrect_logins`';

$reg8log_db->query($query);

$rec=$reg8log_db->fetch_row();

$num=ceil(1/$cleanup_probability);

if(($rec['n']+$num)<=$max_login_attempt_records) return;

if($rec['n']-$max_login_attempt_records>$num) $num=$rec['n']-$max_login_attempt_records;

$query="delete from `incorrect_logins` order by `timestamp` asc limit $num";

$reg8log_db->query($query);

?>