<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

$query="select `email` from `accounts` where `username`='Admin' limit 1";

$reg8log_db->query($query);

$rec3=$reg8log_db->fetch_row();

$email=$rec3['email'];

mail($email, tr('Account/IP blocks alert'), $admin_alert_email_msg);

if($debug_mode) echo "Emailed: $admin_alert_email_msg";

?>