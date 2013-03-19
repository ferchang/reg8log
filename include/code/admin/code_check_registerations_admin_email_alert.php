<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

$query="select * from `admin_registeration_alerts` where `for`='email' limit 1";

$reg8log_db->query($query);

$rec6=$reg8log_db->fetch_row();

$last_reg_alert_email=$rec6['last_alert'];

if(!(!$registeration_alert_emails_min_interval or $req_time>=($last_reg_alert_email+$registeration_alert_emails_min_interval))) {
	$reg8log_db->query("select release_lock($reg_email_alert_lock)");
	return;
}

if($max_registeration_alert_emails) {
	$query='select 1 from `registeration_alert_emails_history` where `timestamp`>='.($req_time-$max_registeration_alert_emails_period);
	if($reg8log_db->result_num($query)>=$max_registeration_alert_emails) {
		$reg8log_db->query("select release_lock($reg_email_alert_lock)");
		return;
	}
}

$new_registerations=$rec6['new_registerations'];
require $index_dir.'include/code/admin/code_check_registerations_alert_threshold.php';

$admin_reg_alert_email_msg='';

if(isset($registerations_alert_threshold_reached)) {

	$admin_reg_alert_email_msg.='- '.sprintf(tr('There were %d new registeration(s).', false, $admin_emails_lang), $new_registerations)."\n";
	
	$query='update `admin_registeration_alerts` set `new_registerations`=0, `last_alert`='.$req_time." where `for`='email' limit 1";
	$reg8log_db->query($query);
	
}

$reg8log_db->query("select release_lock($reg_email_alert_lock)");

if($admin_reg_alert_email_msg) {
	require $index_dir.'include/code/email/admin/code_email_admin_reg_alert_msg.php';
	if($max_registeration_alert_emails) {
		$query="insert into `registeration_alert_emails_history` (`timestamp`) values($req_time)";
		$reg8log_db->query($query);
		require_once $index_dir.'include/config/config_cleanup.php';
		if(mt_rand(1, floor(1/$cleanup_probability))==1) require $index_dir.'include/code/cleanup/code_registeration_alert_emails_history_expired_cleanup.php';
		if(mt_rand(1, floor(1/$cleanup_probability))==1) require $index_dir.'include/code/cleanup/code_registeration_alert_emails_history_size_cleanup.php';
	}
}

?>