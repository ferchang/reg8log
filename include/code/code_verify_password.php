<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

require_once ROOT.'include/code/code_db_object.php';
require_once ROOT.'include/func/func_secure_hash.php';

$query='select `password_hash` from `accounts` where `username`='.$reg8log_db->quote_smart($identified_user).' limit 1';

$reg8log_db->query($query);

$rec=$reg8log_db->fetch_row();

if(isset($_POST['curpass'])) {
	$password=$_POST['curpass'];
	$tmp15=func::tr('the current password that you entered was incorrect!');
}
else {
	$password=$_POST['password'];
	$tmp15=func::tr('the account password that you entered was incorrect!');
}

if(!verify_secure_hash($password, $rec['password_hash'])) $err_msgs[]=$tmp15;

?>