<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

require_once $index_dir.'include/func/func_random.php';

$table_name='accounts';
$field_name='uid';
require $index_dir.'include/code/code_generate_unique_random_id.php';

$autologin_key=random_string(43);

$field_names='`uid`, `username`, `password_hash`, `email`, `gender`, `autologin_key`, `timestamp`';

$username=$reg8log_db->quote_smart($rec['username']);
$email=$reg8log_db->quote_smart($rec['email']);
$gender=$rec['gender'];
$timestamp=$req_time; // should we use $rec['timestamp'] instead?
$password_hash=$reg8log_db->quote_smart($rec['password_hash']);

$field_values="'$rid', $username, $password_hash, $email, '$gender', '$autologin_key', $timestamp";
/* note: rid in the $field_values is not $_GET['rid'];
it was generated in the code_generate_unique_random_id.php */

$query="insert into `accounts` ($field_names) values ($field_values)";
$reg8log_db->query($query);

$query="delete from `pending_accounts` where `username`=$username limit 1";
$reg8log_db->query($query);

$success_msg='<h3>Congratulations!<br>Your registration is completed.</h3>';
$no_specialchars=true;
require $index_dir.'include/page/page_success.php';

?>
