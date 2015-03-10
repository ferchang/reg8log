<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

function get_autologin_ages() {

	global $admin_autologin_ages;
	global $autologin_ages;

	if(isset($_POST['username'])) {
		if(strtolower($_POST['username'])=='admin') return $admin_autologin_ages;
		else return $autologin_ages;
	}
	
	return $autologin_ages;

}

?>