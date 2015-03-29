<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$lock_name=$reg8log_db->quote_smart('reg8log--ban-'.strtolower($_POST['username'])."--$site_key");
$reg8log_db->query("select get_lock($lock_name, -1)");

$username=$reg8log_db->quote_smart($_POST['username']);

$query='update `accounts` set `banned`=0 where `username`='.$username.' limit 1';

$reg8log_db->query($query);

$query='delete from `ban_info` where `username`='.$username.' limit 1';

$reg8log_db->query($query);

$reg8log_db->query("select release_lock($lock_name)");

$success_msg='<h3>'.func::tr('User').' <span style="color: orange">'.htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8').'</span> '.func::tr('unbanned successfully.').'</h3>';
$no_specialchars=true;
$additional_link=array(func::tr('Admin operations'), 'index.php');
require ROOT.'include/page/page_success.php';

require ROOT.'include/code/code_set_submitted_forms_cookie.php';

?>