<?php

if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

//----------------------------------------

// per account anti-'login brute-force' settings

$captcha_threshold=-1; //1-10 / -1: disabled (no captcha) / 0: always

$lockdown_threshold=-1; //1-10 / -1: disabled (no lockdown)

$lockdown_period=12*60*60; //in seconds

$lockdown_bypass_system_enabled=true;
//with lockdown bypass system, owner of a locked down account can attempt to login via a special link sent to the account's email

$max_lockdown_bypass_emails=10; //1-255 / -1: infinite

$dont_block_admin_account=0;
//this setting can be useful e.g. if bad guys try to prevent admin's access to the system by frequently causing his account/IP to be blocked. but be aware that enabling this setting opens the admin account to login brute force attacks, so his account password must be  strong enough to resist attacks.
//see below for possible values for this setting.
//note that some degree of protection with captcha can still be in place with all these settings; only complete blocks are prevented.
//0: admin account is fully protected with the lockdown system (of course, if the lockdown system itself is enabled)
//1: admin account is not protected by the account lockdown system, but can still be protected by the IP block system (if enabled) to some degree.
//2: login to the admin account is exempt from IP blocks, but can still be protected by the account block system (if enabled).
//3: admin account is not protected against brute-force attacks at all (it is excluded from both account and IP based blocks). use this setting only if u have a good reason for it and u know what u r doing. with this setting, your only defence against brute force attacks will be a really strong password.

$allow_users2disable_account_block=0;
//this setting can be useful e.g. if bad guys try to prevent some users' access to the system by frequently causing their accounts/IPs to be blocked. but be aware that enabling this setting opens the account to login brute force attacks, so their account password must be strong enough to resist attacks.
//see below for possible values for this setting.
//note that some degree of protection with captcha can still be in place with all these settings; only complete blocks are prevented.
//0: users' accounts are fully protected with the lockdown system (of course, if the lockdown system itself is enabled) and no user can disable the block system for his account
//1: users can opt to disable the account block system for their own account.
//2: users can opt to disable the IP based block system for their own accounts.
//3: users can opt to disable both the account and IP based blocks for their own accounts.

//----------------------------------------

// per IP anti-'login brute-force' settings

$ip_captcha_threshold=-1; // -1: disabled (no captcha) / 0: always

$ip_lockdown_threshold=-1; // -1: disabled (no ip lockdown)

$ip_lockdown_period=30*60; //in seconds

$ip_lockdown_proportional=true;
// true: incorrect logins count='incorrect logins' divided by 'correct logins'
// true is proper when an IP might be shared between possibly many users.
// Note: several correct logins to one account are counted only once (for security reasons).
// false: incorrect logins count=incorrect_logins

//----------------------------------------

// settings for wrong current passwords entered in the change password and change email forms

$ch_pswd_captcha_threshold=3; // -1: disabled (no captcha) / 0: always

$ch_pswd_max_threshold=6; // 1-255 / -1: disabled
// after $ch_pswd_max_threshold wrong passwords, the account autologin key will be changed (and thus the current user will be logged out)

$ch_pswd_period=12*60*60; //in seconds

//----------------------------------------

?>