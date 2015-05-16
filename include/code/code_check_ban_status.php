<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$tmp6=$GLOBALS['reg8log_db']->quote_smart($_username);

if(REQUEST_TIME>=$_until and $_until!=1) {
	$query='update `accounts` set `banned`=0 where `username`='.$tmp6.' limit 1';
	$GLOBALS['reg8log_db']->query($query);
	$query='delete from `ban_info` where `username`='.$tmp6.' limit 1';
	$GLOBALS['reg8log_db']->query($query);	
	return;
}

$GLOBALS['banned_user']=$_username;
$GLOBALS['ban_until']=$_until;

$query='select * from `ban_info` where `username`='.$tmp6.' limit 1';

if($GLOBALS['reg8log_db']->result_num($query)) {
	$rec=$GLOBALS['reg8log_db']->fetch_row();
	$GLOBALS['ban_reason']=$rec['reason'];
}
else {
	echo 'Warning: No corresponding ban_info record found for banned user!';
	$GLOBALS['ban_reason']='';
}

?>