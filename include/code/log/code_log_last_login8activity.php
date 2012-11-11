<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

require_once $index_dir.'include/code/code_db_object.php';

if(isset($identified_user)) $tmp28=$reg8log_db->quote_smart($identified_user);
else if(isset($banned_user)) $tmp28=$reg8log_db->quote_smart($banned_user);

$query='update `accounts` set ';

switch($log_activity) {
	case 1:
		$query.='`last_login`='.$req_time;
	break;
	case 2:
		$query.='`last_activity`='.$req_time;
	break;
	case 3:
		$query.='`last_login`='.$req_time.', `last_activity`='.$req_time;
	break;
}

$query.=' where `username`='.$tmp28.' limit 1';

$reg8log_db->query($query);

?>