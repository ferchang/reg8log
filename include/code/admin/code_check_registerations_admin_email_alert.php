<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

$query="select * from `admin_reg_alerts` where `for`='email' limit 1";

$reg8log_db->query($query);

$rec6=$reg8log_db->fetch_row();

$last_reg_alert_email=$rec6['last_alert'];

if(!(!$registerations_alert_emails_min_interval or $req_time>=($last_reg_alert_email+$registerations_alert_emails_min_interval))) {
	$reg8log_db->query("select release_lock($reg_email_alert_lock)");
	return;
}

$new_regs=$rec6['new_regs'];

$admin_reg_alert_email_msg='';

if($new_regs>=$registerations_alert_threshold) {

	$admin_reg_alert_email_msg.='- There were '.$new_regs." new registeration(s).\n";
	
	$query='update `admin_reg_alerts` set `new_regs`=0, `last_alert`='.$req_time." where `for`='email' limit 1";
	$reg8log_db->query($query);
	
}

$reg8log_db->query("select release_lock($reg_email_alert_lock)");

if($admin_reg_alert_email_msg) require $index_dir.'include/code/email/code_email_admin_reg_alert_msg.php';

?>