<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");



require_once $index_dir.'include/class/class_db.php';
require $index_dir.'include/config/config_dbms.php';

$reg8log_db=new reg8log_db($reg8log_dbms_info['host'], $reg8log_dbms_info['user'], $reg8log_dbms_info['pass'], $reg8log_dbms_info['db'], true);

$reg8log_db->query("SET NAMES 'utf8'");

?>