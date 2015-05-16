<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$table_name='accounts';
$field_name='uid';
require ROOT.'include/code/code_generate_unique_random_id.php';

$autologin_key=func::random_string(43);

$password_hash=$GLOBALS['reg8log_db']->quote_smart(bcrypt::hash($_POST['password']));
$username=$GLOBALS['reg8log_db']->quote_smart($_POST['username']);
$email=$GLOBALS['reg8log_db']->quote_smart($_POST['email']);
$timestamp=REQUEST_TIME;

$query="insert into `accounts` (`uid`, `username`, `password_hash`, `email`, `gender`, `autologin_key`, `timestamp`) values ('$rid', $username, $password_hash, $email, 'n', '$autologin_key', $timestamp)";

$GLOBALS['reg8log_db']->query($query);

?>
