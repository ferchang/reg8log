<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

function get_relative_root_path() {

	$relative_root='';
	$depth=substr_count($_SERVER['SCRIPT_FILENAME'], '/', strlen(ROOT));
	for($i=0; $i<$depth; $i++) $relative_root.='../';

	return $relative_root;

}

?>
