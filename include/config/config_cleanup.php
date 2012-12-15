<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

//--------------------------------

$cleanup_probability=0.01;

//--------------------------------

$max_nonexistent_users_records=100*1000;

//--------------------------------

$max_ip_incorrect_login_records=1000*1000;

$max_ip_incorrect_logins_decs_records=100*1000;

//--------------------------------

$max_security_logs_records=10*1000;
//note that this is used in two tables, so actual max number of log records can be twice this.

//--------------------------------

$max_ajax_check_usernames_records=100*1000;

//--------------------------------

$max_block_alert_emails_history_records=10*1000;

//--------------------------------

$max_registeration_alert_emails_history_records=10*1000;

//--------------------------------

$max_registerations_history_records=10*1000;

?>