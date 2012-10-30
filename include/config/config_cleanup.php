<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

$cleanup_probability=0.01;

$max_nonexistent_users_records=100*1000;

//--------------------

$max_login_attempt_records=1000*1000;
// note that we have two tables (ip_correct/ip_incorrect_logins) with this maximum number of records
// but it seems that ip_correct_logins table can reach this max number of records much harder,
// because it needs one account be existent per record and several successful logins to one account yield only one record

$max_security_logs_records=1000;
//note that this is used in two tables, so actual max number of log records can be twice this

$max_ip_ajax_check_usernames_records=100*1000;

?>