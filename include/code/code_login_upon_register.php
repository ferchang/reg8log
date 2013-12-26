<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

$query='select * from `accounts` where `username`='.$reg8log_db->quote_smart($_username);

$reg8log_db->query($query);

require_once $index_dir.'include/class/class_cookie.php';
require_once $index_dir.'include/class/class_user.php';
require $index_dir.'include/config/config_identify.php';

$tmp13=new hm_user($identify_structs);

$tmp13->user_info=$reg8log_db->fetch_row();

$tmp13->save_identity($login_upon_register_age, false, true);

?>
