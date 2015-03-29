<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(!config::get('alert_admin_about_registerations') or (config::get('alert_admin_about_registerations')==1 and isset($pending_reg)) or (config::get('alert_admin_about_registerations')==2 and !isset($pending_reg))) return;

if(config::get('registeration_alert_type')==1) {
		$query="update `admin_registeration_alerts` set `new_registerations`=`new_registerations`+1 where `for`='visit' limit 1";
		$reg8log_db->query($query);
	}
	else if(config::get('registeration_alert_type')==2) {
		$reg_email_alert_lock="'".'reg8log--admin_registerations_email_alert--'.$site_key."'";
		$reg8log_db->query("select get_lock($reg_email_alert_lock, -1)");
		$query="update `admin_registeration_alerts` set `new_registerations`=`new_registerations`+1 where `for`='email' limit 1";
		$reg8log_db->query($query);
		if(config::get('registerations_alert_threshold_period')) {
			$query="insert into `registerations_history` (`timestamp`) values($req_time)";
			$reg8log_db->query($query);
			if(mt_rand(1, floor(1/config::get('cleanup_probability')))==1) require ROOT.'include/code/cleanup/code_registerations_history_expired_cleanup.php';
			if(mt_rand(1, floor(1/config::get('cleanup_probability')))==1) require ROOT.'include/code/cleanup/code_registerations_history_size_cleanup.php';
		}
		require ROOT.'include/code/admin/code_check_registerations_admin_email_alert.php';
	}
	else {
		$reg_email_alert_lock="'".'reg8log--admin_registerations_email_alert--'.$site_key."'";
		$reg8log_db->query("select get_lock($reg_email_alert_lock, -1)");
		$query="update `admin_registeration_alerts` set `new_registerations`=`new_registerations`+1 limit 2";
		$reg8log_db->query($query);
		if(config::get('registerations_alert_threshold_period')) {
			$query="insert into `registerations_history` (`timestamp`) values($req_time)";
			$reg8log_db->query($query);
			if(mt_rand(1, floor(1/config::get('cleanup_probability')))==1) require ROOT.'include/code/cleanup/code_registerations_history_expired_cleanup.php';
			if(mt_rand(1, floor(1/config::get('cleanup_probability')))==1) require ROOT.'include/code/cleanup/code_registerations_history_size_cleanup.php';
		}
		require ROOT.'include/code/admin/code_check_registerations_admin_email_alert.php';
	}

?>