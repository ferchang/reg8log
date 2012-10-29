<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

$max_password_reset_emails=10; //1-255 / -1: infinite
//maximum number of emails that can be sent in the priod time

$password_reset_period=24*60*60; //in seconds

$change_autologin_key_upon_new_password=true;

?>