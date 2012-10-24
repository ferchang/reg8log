<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");
$parent_page=true;

require $index_dir.'include/code/code_calc_protection.php';

$query="update `accounts` set `block_disable`='$disables', `last_protection`= $protection where `username`=".$reg8log_db->quote_smart($identified_user).' limit 1';

$reg8log_db->query($query);

?>
