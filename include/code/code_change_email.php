<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");
$parent_page=true;

if(!isset($index_dir)) $index_dir='';

$query='update `accounts` set `email`='.$reg8log_db->quote_smart($_POST['newemail']).' where `username`='.$reg8log_db->quote_smart($identified_username).' limit 1';

$reg8log_db->query($query);

?>
