<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

$query='delete from `ban_info` where `until`!=1 and `until`<'.time();

$reg8log_db->query($query);

?>