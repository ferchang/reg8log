<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

function my_exit($string) {
	
	exit("<span {$GLOBALS['page_dir']}>$string</span>");

}

?>
