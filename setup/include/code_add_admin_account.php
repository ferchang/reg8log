<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

require_once ROOT.'include/func/func_random.php';

$table_name='accounts';
$field_name='uid';
require ROOT.'include/code/code_generate_unique_random_id.php';

$autologin_key=random_string(43);

$password_hash=$reg8log_db->quote_smart(bcrypt::hash($_POST['password']));
$username=$reg8log_db->quote_smart($_POST['username']);
$email=$reg8log_db->quote_smart($_POST['email']);
$timestamp=$req_time;

$query="insert into `accounts` (`uid`, `username`, `password_hash`, `email`, `gender`, `autologin_key`, `timestamp`) values ('$rid', $username, $password_hash, $email, 'n', '$autologin_key', $timestamp)";

$reg8log_db->query($query);

?>
