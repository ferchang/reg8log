<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

require_once $index_dir.'include/code/code_db_object.php';

if($_POST['ban_type']==='duration') $until=$req_time+$_POST['years']*(365*24*60*60)+$_POST['months']*(30*24*60*60)+$_POST['days']*(24*60*60)+$_POST['hours']*(60*60);
else $until=1;

if(!is_numeric($until)) exit("<center><h3>Error: Duration not a number!</h3></center>");

require_once $index_dir.'include/code/code_fetch_site_vars.php';

$lock_name=$reg8log_db->quote_smart('reg8log--ban-'.strtolower($_POST['username'])."--$site_key");
$reg8log_db->query("select get_lock($lock_name, -1)");

$username=$reg8log_db->quote_smart($_POST['username']);

$query='update `accounts` set `banned`='.$until.' where `username`='.$username.' limit 1';

$reg8log_db->query($query);

$reason=$reg8log_db->quote_smart($_POST['reason']);

$query='replace into `ban_info` (`username`, `until`, `reason`) values('."$username, $until, $reason)";

$reg8log_db->query($query);

$reg8log_db->query("select release_lock($lock_name)");

$success_msg='<h3>'.tr('User').' <span style="color: orange">'.htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8').'</span> '.tr('banned successfully.').'</h3>';
$no_specialchars=true;
$additional_link=array(tr('Admin operations'), 'index.php');
require $index_dir.'include/page/page_success.php';

require $index_dir.'include/config/config_cleanup.php';

if(mt_rand(1, floor(1/$cleanup_probability))==1) require $index_dir.'include/code/cleanup/code_ban_info_expired_cleanup.php';

require $index_dir.'include/code/code_set_submitted_forms_cookie.php';

?>