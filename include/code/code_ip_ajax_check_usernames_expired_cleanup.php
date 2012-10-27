<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

$expired=time()-$max_ajax_check_usernames_period;

$query="delete from `ip_ajax_check_usernames` where `timestamp` < $expired";

$reg8log_db->query($query);

?>