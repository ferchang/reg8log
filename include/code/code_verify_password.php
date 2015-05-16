<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$query='select `password_hash` from `accounts` where `username`='.$GLOBALS['reg8log_db']->quote_smart($identified_user).' limit 1';

$GLOBALS['reg8log_db']->query($query);

$rec=$GLOBALS['reg8log_db']->fetch_row();

if(isset($_POST['curpass'])) {
	$password=$_POST['curpass'];
	$tmp15=func::tr('the current password that you entered was incorrect!');
}
else {
	$password=$_POST['password'];
	$tmp15=func::tr('the account password that you entered was incorrect!');
}

if(!bcrypt::verify($password, $rec['password_hash'])) $err_msgs[]=$tmp15;

?>