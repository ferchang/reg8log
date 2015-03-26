<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(!config::get('alert_admin_about_account_blocks') and !config::get('alert_admin_about_ip_blocks')) return;

if(isset($_COOKIE['reg8log_dont_disturb']) and $_COOKIE['reg8log_dont_disturb']==='1') return;

require_once ROOT.'include/code/code_db_object.php';

$query="select * from `admin_block_alerts` where `for`='visit' limit 1";

$reg8log_db->query($query);

$rec=$reg8log_db->fetch_row();

$admin_alert_visit_msg='';

if($rec['new_account_blocks'] and in_array(config::get('alert_admin_about_account_blocks'), array(1, 3, 4, 6))) {
	$new_account_blocks=$rec['new_account_blocks'];
	require ROOT.'include/code/admin/code_check_account_blocks_alert_threshold.php';
	if(isset($account_blocks_alert_threshold_reached)) $admin_alert_visit_msg=	'- '.sprintf(func::tr('There were %d new account block(s).'), $new_account_blocks)."\n";
}

if($rec['new_ip_blocks'] and in_array(config::get('alert_admin_about_ip_blocks'), array(1, 3))) {
	$new_ip_blocks=$rec['new_ip_blocks'];
	require ROOT.'include/code/admin/code_check_ip_blocks_alert_threshold.php';
	if(isset($ip_blocks_alert_threshold_reached)) $admin_alert_visit_msg.='- '.sprintf(func::tr('There were %d new IP block(s).'), $new_ip_blocks)."\n";
}

//----------------------------------------

if(config::get('registeration_alert_type')!=1 and config::get('registeration_alert_type')!=3) return;

$query="select * from `admin_registeration_alerts` where `for`='visit' limit 1";

$reg8log_db->query($query);

$rec8=$reg8log_db->fetch_row();

$new_registerations=$rec8['new_registerations'];

if($new_registerations<config::get('registerations_alert_threshold')) return;

if($admin_alert_visit_msg) $admin_alert_visit_msg.="\n";
$admin_alert_visit_msg.='- '.sprintf(func::tr('There were %d new registeration(s).'), $new_registerations);

?>