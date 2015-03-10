<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(!isset($site_salt)) if(isset($_COOKIE['reg8log_site_salt'])) $site_salt=$_COOKIE['reg8log_site_salt'];
else {
	require ROOT.'include/code/code_fetch_site_vars.php';
	setcookie('reg8log_site_salt', $site_salt, 0, '/', null, HTTPS, true);
}

?>