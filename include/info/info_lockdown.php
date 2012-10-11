<?php

if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

//----------------------------------------

// per account anti-'login brute-force' settings

$captcha_threshold=3; //1-10 / -1: disabled (no captcha) / 0: always

$lockdown_threshold=6; //1-10 / -1: disabled (no lockdown)

$lockdown_period=12*60*60; //in seconds

$lockdown_bypass_system_enabled=true;
//with lockdown bypass system, owner of a locked down account can attempt to login via a special link sent to the account's email

$max_lockdown_bypass_emails=10; //1-255 / -1: infinite

//----------------------------------------

// per IP anti-'login brute-force' settings

$ip_captcha_threshold=7; // -1: disabled (no captcha) / 0: always

$ip_lockdown_threshold=14; // -1: disabled (no ip lockdown)

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