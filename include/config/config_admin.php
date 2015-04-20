<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$show_statistics_in_admin_operations_page=true;

$admin_operations_require_password=1;//whether entering admin password is required when performing admin operations (such as confirming or deleting an account).
//possible values: 0 no / 1: yes / any bigger number n: yes but dont require re-entering password if it was entered in the past n seconds (note this remembering will not work after browser is closed because the related cookie will be deleted - this is intentional for security)

$admin_error_log_access=2;//0: no access, 1: view only access, 2: view and clear access
//note that when debug_mode is on, everybody can view and clear the error log regardless of the value of this config var.

?>