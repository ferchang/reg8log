<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$table_name='accounts';
$field_name='uid';
require ROOT.'include/code/code_generate_unique_random_id.php';

$autologin_key=func::random_string(43);

$field_names='`uid`, `username`, `password_hash`, `email`, `gender`, `autologin_key`, `timestamp`, `email_verified`';

$username=$GLOBALS['reg8log_db']->quote_smart($rec['username']);
$email=$GLOBALS['reg8log_db']->quote_smart($rec['email']);
$gender=$rec['gender'];
$timestamp=REQUEST_TIME; // should we use $rec['timestamp'] instead?
$password_hash=$GLOBALS['reg8log_db']->quote_smart($rec['password_hash']);
$email_verified=$rec['email_verified'];

$field_values="'$rid', $username, $password_hash, $email, '$gender', '$autologin_key', $timestamp, $email_verified";
/* note: rid in the $field_values is not $_GET['rid'];
it was generated in the code_generate_unique_random_id.php */

$query="insert into `accounts` ($field_names) values ($field_values)";
$GLOBALS['reg8log_db']->query($query);

$query="delete from `pending_accounts` where `username`=$username limit 1";
$GLOBALS['reg8log_db']->query($query);

$success_msg=func::tr('Account activated msg');
$no_specialchars=true;

require ROOT.'include/code/log/code_log_registeration.php';

?>
