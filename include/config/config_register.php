<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

$registeration_enabled=true;

$password_refill=2; //0: disabled / 1: enabled if client javascript on / 2: enabled

$email_verification_needed=false;

$admin_confirmation_needed=false;

$email_verification_time=24*60*60; //in seconds

$admin_confirmation_time=30*24*60*60; //in seconds

$login_upon_register=true;

$max_activation_emails=10; //1-255 / -1: infinite

$can_notify_user_about_admin_action=true; // with this set to true user can be notified (via email) when his pending account is approved or rejected/deleted by admin.

//----------------------------------------

$ajax_check_username=true;//whether username availability can be checked via ajax in the register form
//u may want to disable it in some strict security environments
//although usernames can still be checked via the register system, it will need passing a captcha test every time the username is changed

$max_ajax_check_usernames=20;
//maximum number of ajax check username availability in $max_ajax_check_usernames_period per each IP.
//0: no limit

$max_ajax_check_usernames_period=60*60;//the duration (in seconds) in which each IP can check $max_ajax_check_usernames usernames' availability via ajax (in the register form).

$reset_clients_ajax_check_usernames_upon_register=true;
//with this set to true, a client's ajax username availability check records will be deleted from the ip_ajax_check_usernames table when the client registers successfully (which means it passed a captcha). this lets human users to use the ajax username availability check again.

?>