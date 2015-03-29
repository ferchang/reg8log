<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

require ROOT.'include/config/config_dbms.php';

$reg8log_db=$GLOBALS['reg8log_db']=new reg8log_db($reg8log_dbms_info['host'], $reg8log_dbms_info['user'], $reg8log_dbms_info['pass'], $reg8log_dbms_info['db'], true);

$reg8log_db->query("SET NAMES 'utf8'");
mysql_set_charset('utf8');
//Note: i included the "SET NAMES 'utf8'" query too because mysql_set_charset doesn't exist in PHP<5.2.3
//mysql_set_charset is the more secure way to set charset (see this: http://stackoverflow.com/a/12118602)

?>