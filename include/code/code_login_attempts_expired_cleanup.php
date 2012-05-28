<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(!isset($index_dir)) $index_dir='';

$expired=time()-$ip_lockdown_period;

$query="delete from `correct_logins` where `timestamp` < $expired";

$reg8log_db->query($query);

$query="delete from `incorrect_logins` where `timestamp` < $expired";

$reg8log_db->query($query);

?>