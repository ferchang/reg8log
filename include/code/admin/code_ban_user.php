<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

if($_POST['ban_type']==='duration') $until=REQUEST_TIME+$_POST['years']*(365*24*60*60)+$_POST['months']*(30*24*60*60)+$_POST['days']*(24*60*60)+$_POST['hours']*(60*60);
else $until=1;

if(!is_numeric($until)) exit("<center><h3>Error: Duration not a number!</h3></center>");

$lock_name=$GLOBALS['reg8log_db']->quote_smart('reg8log--ban-'.strtolower($_POST['username']).'--'.SITE_KEY);
$GLOBALS['reg8log_db']->query("select get_lock($lock_name, -1)");

$username=$GLOBALS['reg8log_db']->quote_smart($_POST['username']);

$query='update `accounts` set `banned`='.$until.' where `username`='.$username.' limit 1';

$GLOBALS['reg8log_db']->query($query);

$reason=$GLOBALS['reg8log_db']->quote_smart($_POST['reason']);

$query='replace into `ban_info` (`username`, `until`, `reason`) values('."$username, $until, $reason)";

$GLOBALS['reg8log_db']->query($query);

$GLOBALS['reg8log_db']->query("select release_lock($lock_name)");

$success_msg='<h3><span style="background: red; padding: 5px; border: medium solid #88d">'.func::tr('User').' <span style="color: yellow">'.htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8').'</span> '.func::tr('banned successfully.').'</span></h3>';
$no_specialchars=true;
$additional_link=array(func::tr('Admin operations'), 'index.php');
require ROOT.'include/page/page_success.php';

if(mt_rand(1, floor(1/config::get('cleanup_probability')))===1) require ROOT.'include/code/cleanup/code_ban_info_expired_cleanup.php';

require ROOT.'include/code/code_set_submitted_forms_cookie.php';

?>