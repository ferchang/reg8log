<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$debug_mode=true;

$db_installed=false;

$lang='en';
// Default language
// Currently, only English (en) and Persian/Farsi (fa) are supported.

$admin_emails_lang='';
// Default language for admin alert emails
// if empty, $lang will be used.

$log_errors=E_ALL;//set to E_ERROR, E_ALL, E_ERROR|E_WARNING, etc. set to false/0 to disable error logging

$config_cache_validation_interval=15*60;//in secs / a value of 0 means always validate the cache (against original config files by comparing their last modification times with that of the config cache)
//notice that the value of this config var is ignored when in debug mode ($debug_mode=true) / in debug mode the config cache is always validated (validation happens once per each request)

?>