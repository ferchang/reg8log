<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$query='select count(*) as `n` from `ajax_check_usernames`';

$reg8log_db->query($query);

$rec=$reg8log_db->fetch_row();

$num=ceil(1/config::get('cleanup_probability'));

if(($rec['n']+$num)>config::get('max_ajax_check_usernames_records')) {
	if($rec['n']-config::get('max_ajax_check_usernames_records')>$num) $num=$rec['n']-config::get('max_ajax_check_usernames_records');
	$query="delete from `ajax_check_usernames` order by `timestamp` asc limit $num";
	$reg8log_db->query($query);
}

?>