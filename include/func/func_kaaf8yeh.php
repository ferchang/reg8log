<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

function fix_kaaf8yeh($string) {

	return str_replace(array('ي', 'ك'), array('ی', 'ک'), $string);

}

?>
