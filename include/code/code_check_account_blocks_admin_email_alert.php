<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

require_once $index_dir.'include/config/config_security_logs.php';

require_once $index_dir.'include/code/code_db_object.php';

$query="select * from `admin_alerts` where `for`='email' limit 1";

$reg8log_db->query($query);

$rec2=$reg8log_db->fetch_row();

$last_alert_email=$rec2['last_alert'];

if(!(!$alert_emails_min_interval or time()>=($last_alert_email+$alert_emails_min_interval)) and !$no_alert_limits) {
	$reg8log_db->query("select release_lock('$lock_name2')");
	return;
}

$new_account_blocks=$rec2['new_account_blocks'];
require $index_dir.'include/code/code_check_account_blocks_alert_threshold.php';

$admin_alert_email_msg='';

if(isset($account_blocks_alert_threshold_reached) or $no_alert_limits) {

	if($no_alert_limits) $admin_alert_email_msg="- Admin account was blocked.\n";

	$admin_alert_email_msg.='- There were '.$new_account_blocks." new account block(s).\n";
	
	$query='update `admin_alerts` set `new_account_blocks`=0, `last_alert`='.time()." where `for`='email' limit 1";
	$reg8log_db->query($query);
	
}

$reg8log_db->query("select release_lock('$lock_name2')");

if($admin_alert_email_msg) require_once $index_dir.'include/code/code_email_admin_alert_msg.php';

?>