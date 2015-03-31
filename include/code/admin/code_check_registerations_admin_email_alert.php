<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$query="select * from `admin_registeration_alerts` where `for`='email' limit 1";

$reg8log_db->query($query);

$rec6=$reg8log_db->fetch_row();

$last_reg_alert_email=$rec6['last_alert'];

if(!(!config::get('registeration_alert_emails_min_interval') or $req_time>=($last_reg_alert_email+config::get('registeration_alert_emails_min_interval')))) {
	$reg8log_db->query("select release_lock($reg_email_alert_lock)");
	return;
}

if(config::get('max_registeration_alert_emails')) {
	$query='select count(*) from `registeration_alert_emails_history` where `timestamp`>='.($req_time-config::get('max_registeration_alert_emails_period'));
	if($reg8log_db->count_star($query)>=config::get('max_registeration_alert_emails')) {
		$reg8log_db->query("select release_lock($reg_email_alert_lock)");
		return;
	}
}

$new_registerations=$rec6['new_registerations'];
require ROOT.'include/code/admin/code_check_registerations_alert_threshold.php';

$admin_reg_alert_email_msg='';

if(isset($registerations_alert_threshold_reached)) {

	$admin_reg_alert_email_msg.='- '.sprintf(func::tr('There were %d new registeration(s).', false, $admin_emails_lang), $new_registerations)."\n";
	
	$query='update `admin_registeration_alerts` set `new_registerations`=0, `last_alert`='.$req_time." where `for`='email' limit 1";
	$reg8log_db->query($query);
	
}

$reg8log_db->query("select release_lock($reg_email_alert_lock)");

if($admin_reg_alert_email_msg) {
	require ROOT.'include/code/email/admin/code_email_admin_reg_alert_msg.php';
	if(config::get('max_registeration_alert_emails')) {
		$query="insert into `registeration_alert_emails_history` (`timestamp`) values($req_time)";
		$reg8log_db->query($query);
		if(mt_rand(1, floor(1/config::get('cleanup_probability')))===1) require ROOT.'include/code/cleanup/code_registeration_alert_emails_history_expired_cleanup.php';
		if(mt_rand(1, floor(1/config::get('cleanup_probability')))===1) require ROOT.'include/code/cleanup/code_registeration_alert_emails_history_size_cleanup.php';
	}
}

?>