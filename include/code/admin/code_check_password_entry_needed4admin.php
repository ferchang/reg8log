<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(!$admin_operations_require_password) return;

$password_check_needed=true;

if($admin_operations_require_password==1) return;

if(!isset($_COOKIE['reg8log_password_check_key'])) return;

$query='select * from `admin` limit 1';

$reg8log_db->query($query);

$rec=$reg8log_db->fetch_row();

if($req_time>$rec['last_password_check']+$admin_operations_require_password) return;

if($rec['password_check_key']!=$_COOKIE['reg8log_password_check_key']) return;

unset($password_check_needed);

?>