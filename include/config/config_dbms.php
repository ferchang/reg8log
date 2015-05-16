<?php

if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

//set the information needed for connecting to MySQL DBMS here
$dbms_info=array(
	'host'=>'localhost',
	'user'=>'root',
	'pass'=>'',
	'db'=>'reg8log'
);

?>
