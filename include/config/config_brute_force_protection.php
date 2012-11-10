<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

//----------------------------------------

// per account anti-'login brute-force' settings

//for normal users accounts >

$account_captcha_threshold=-1; //1-10 / -1: disabled (no captcha) / 0: always

$account_block_threshold=-1; //1-10 / -1: disabled (no block)

$account_block_period=12*60*60; //in seconds

//< for normal users accounts

//for Admin account >

$admin_account_captcha_threshold=-1; //1-10 / -1: disabled (no captcha) / 0: always

$admin_account_block_threshold=-1; //1-10 / -1: disabled (no block)

$admin_account_block_period=12*60*60; //in seconds

//< for Admin account

$block_bypass_system_enabled=3;
//with block-bypass system, owner of a locked down account can attempt to login via a special link sent to the account's email
//0: disabled
//1: enabled only for admin account
//2: enabled only for accounts other than admin
//3: enabled for all accounts

$block_bypass_max_incorrect_logins=6;//1-255 / 0: infinite
//even with the block-bypass system, number of incorrect logins can be limited for security reasons.

$block_bypass_system_also4ip_block=true;
//if enabled, users can also bypass ip blocks with the block-bypass system

$max_block_bypass_emails=10; //1-255 / -1: infinite

$allow_users2disable_blocks=0;
//this setting can be useful e.g. if bad guys try to prevent some users' access to the system by frequently causing their accounts/IPs to be blocked. but be aware that enabling this setting opens the account to login brute force attacks, so their account password must be strong enough to resist attacks.
//see below for possible values for this setting.
//note that some degree of protection with captcha can still be in place with all these settings; only complete blocks are prevented.
//0: users' accounts are fully protected with the block system (of course, if the block system itself is enabled) and users cannot disable the block system for their accounts
//1: users can disable the IP based blocks for their own accounts.
//2: users can disable the account blocks for their own accounts.
//3: users can disable both the account and IP based blocks for their own accounts.

//----------------------------------------

// per IP anti-'login brute-force' settings

//for normal users accounts >

$ip_captcha_threshold=-1; // -1: disabled (no captcha) / 0: always

$ip_block_threshold=3; // -1: disabled (no ip block)

$ip_block_period=12*60*60; //in seconds

//< for normal users accounts

//for Admin account >

$admin_ip_captcha_threshold=-1; // -1: disabled (no captcha) / 0: always

$admin_ip_block_threshold=3; // -1: disabled (no ip block)

$admin_ip_block_period=12*60*60; //in seconds

//< for Admin account

//----------------------------------------

// settings for wrong current passwords entered in the change password and change email forms

$ch_pswd_captcha_threshold=3; // -1: disabled (no captcha) / 0: always

$ch_pswd_max_threshold=6; // 1-255 / -1: disabled
// after $ch_pswd_max_threshold wrong passwords, the account autologin key will be changed (and thus the current user will be logged out)

$ch_pswd_period=12*60*60; //in seconds

//----------------------------------------

?>