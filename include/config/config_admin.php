<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

$show_statistics_in_admin_operations_page=true;

$admin_operations_require_password=2*60;//whether entering admin password is required when performing admin operations (such as confirming or deleting an account).
//possible values: 0 no / 1: yes / any bigger number n: yes but dont require re-entering password if it was entered in the past n seconds.

?>