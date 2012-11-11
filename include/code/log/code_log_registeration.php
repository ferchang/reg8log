<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

require $index_dir.'include/config/config_register.php';

if(!$alert_admin_about_registerations or ($alert_admin_about_registerations==1 and isset($pending_reg)) or ($alert_admin_about_registerations==2 and !isset($pending_reg))) return;

if($registerations_alert_type==1) {
		$query="update `admin_reg_alerts` set `new_regs`=`new_regs`+1 where `for`='visit' limit 1";
		$reg8log_db->query($query);
		require $index_dir.'include/code/admin/code_check_registerations_admin_email_alert.php';
	}
	else if($registerations_alert_type==2) {
		if(!isset($site_key)) require_once $index_dir.'include/code/code_fetch_site_vars.php';
		$reg_email_alert_lock="'".'reg8log--admin_registerations_email_alert--'.$site_key."'";
		$reg8log_db->query("select get_lock($reg_email_alert_lock, -1)");
		$query="update `admin_reg_alerts` set `new_regs`=`new_regs`+1 where `for`='email' limit 1";
		$reg8log_db->query($query);
		require $index_dir.'include/code/admin/code_check_registerations_admin_email_alert.php';
	}
	else {
		if(!isset($site_key)) require_once $index_dir.'include/code/code_fetch_site_vars.php';
		$reg_email_alert_lock="'".'reg8log--admin_registerations_email_alert--'.$site_key."'";
		$reg8log_db->query("select get_lock($reg_email_alert_lock, -1)");
		$query="update `admin_reg_alerts` set `new_regs`=`new_regs`+1 limit 2";
		$reg8log_db->query($query);
		require $index_dir.'include/code/admin/code_check_registerations_admin_email_alert.php';
	}

?>