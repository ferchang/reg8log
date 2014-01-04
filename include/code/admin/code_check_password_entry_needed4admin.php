<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(!$admin_operations_require_password) return;

$password_check_needed=true;

if($admin_operations_require_password==1) return;

if(!isset($_COOKIE['reg8log_password_check_key'])) return;

$query='select * from `admin` limit 1';

$reg8log_db->query($query);

$tmp42=$reg8log_db->fetch_row();

if($req_time>$tmp42['last_password_check']+$admin_operations_require_password) {
	setcookie('reg8log_password_check_key', false, mktime(12,0,0,1, 1, 1990), '/', null, $https, true);
	return;
}

if($tmp42['password_check_key']!=$_COOKIE['reg8log_password_check_key']) {
	setcookie('reg8log_password_check_key', false, mktime(12,0,0,1, 1, 1990), '/', null, $https, true);
	return;
}

unset($password_check_needed);

?>