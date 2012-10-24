<?php

if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

//----------------------------------------

$keep_expired_block_log_records_for=0;
/*
-1: don't keep expired block log records
0: keep unlimitedly (note that records can still be deleted due to $max_security_logs_records)
a positive integer: keep up to this number of seconds after block expiration
*/

$alert_admin_about_account_blocks=1;
//0: no
//1: when he visits
//2: with email
//3: with both methods
//if u want to alert admin only when his own account is blocked, add 3 to the above numbers
//example: 4 means inform admin when he visits and only when his own account was blocked

$account_blocks_alert_threshold=1;
//alert only when this minimum number of account blocks has occured in the past 24 hours
//u can also use a percentage of the total user accounts of the system by specifying a percentage string (precede the number with a percent sign in a string). example: $account_blocks_alert_threshold='%10';

$alert_admin_about_ip_blocks=3;
//0: no
//1: when admin visits
//2: with email
//3: with both methods

$ip_blocks_alert_threshold=1;
//alert only when this minimum number of IP blocks has accured in the past 24 hours
//u can also use a percentage of the total user accounts of the system by specifying a percentage string (precede the number with a percent sign in a string). example: $ip_blocks_alert_threshold='%10';

$alert_emails_min_interval=0;
//don't send alert emails with less than this interval (in seconds)

$exempt_admin_account_from_alert_limits=true;
//whether to exempt admin account block alerts from $account_blocks_alert_threshold and $alert_emails_min_interval limitations

?>