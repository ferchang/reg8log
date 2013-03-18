<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

$query="select `email` from `accounts` where `username`='Admin' limit 1";

$reg8log_db->query($query);

$rec7=$reg8log_db->fetch_row();

$email=$rec7['email'];

mail($email, tr('Registeration(s) alert'), $admin_reg_alert_email_msg);

if($debug_mode) echo "Emailed: $admin_reg_alert_email_msg";

?>