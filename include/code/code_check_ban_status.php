<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");



$tmp6=$reg8log_db->quote_smart($_username);

if(time()>=$_until and $_until!=1) {
	$query='update `accounts` set `banned`=0 where `username`='.$tmp6.' limit 1';
	$reg8log_db->query($query);
	$query='delete from `ban_info` where `username`='.$tmp6.' limit 1';
	$reg8log_db->query($query);	
	return;
}

global $banned_user;
global $ban_until;
global $ban_reason;

$banned_user=$_username;
$ban_until=$_until;

$query='select * from `ban_info` where `username`='.$tmp6.' limit 1';

if($reg8log_db->result_num($query)) {
	$rec=$reg8log_db->fetch_row();
	$ban_reason=$rec['reason'];
}
else {
	echo 'Warning: No corresponding ban_info record found for banned user!';
	$ban_reason='';
}

?>