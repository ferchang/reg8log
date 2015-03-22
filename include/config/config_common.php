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

?>