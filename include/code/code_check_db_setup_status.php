<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$query="select * from `dummy`";

$GLOBALS['reg8log_db']->auto_abort=false;
if($GLOBALS['reg8log_db']->result_num($query)) define('DB_INSTALLED', true);
else define('DB_INSTALLED', false);
$GLOBALS['reg8log_db']->auto_abort=true;

?>