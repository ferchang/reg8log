<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

require_once $index_dir.'include/config/config_security_logs.php';

require_once $index_dir.'include/code/code_db_object.php';

$query="select * from `admin_block_alerts` where `for`='email' limit 1";

$reg8log_db->query($query);

$rec2=$reg8log_db->fetch_row();

$last_alert_email=$rec2['last_alert'];

if(!(!$alert_emails_min_interval or $req_time>=($last_alert_email+$alert_emails_min_interval)) and !$no_alert_limits) {
	$reg8log_db->query("select release_lock('$lock_name2')");
	return;
}

if($max_alert_emails) {
	$query='select count(*) from `block_alert_emails_history` where `timestamp`>='.($req_time-$max_alert_emails_period);
	if($reg8log_db->count_star($query)>=$max_alert_emails) {
		$reg8log_db->query("select release_lock('$lock_name2')");
		return;
	}
}

$new_account_blocks=$rec2['new_account_blocks'];
require $index_dir.'include/code/admin/code_check_account_blocks_alert_threshold.php';

$admin_alert_email_msg='';

if(isset($account_blocks_alert_threshold_reached) or $no_alert_limits) {

	if($no_alert_limits) $admin_alert_email_msg='- '.tr('Admin account was blocked.', false, $admin_emails_lang)."\n";

	$admin_alert_email_msg.='- '.sprintf(tr('There were %d new account block(s).', false, $admin_emails_lang), $new_account_blocks)."\n";
	
	$query='update `admin_block_alerts` set `new_account_blocks`=0, `last_alert`='.$req_time." where `for`='email' limit 1";
	$reg8log_db->query($query);
	
}

$reg8log_db->query("select release_lock('$lock_name2')");

if($admin_alert_email_msg) {
	require $index_dir.'include/code/email/admin/code_email_admin_alert_msg.php';
	if($max_alert_emails) {
		$query="insert into `block_alert_emails_history` (`timestamp`) values($req_time)";
		$reg8log_db->query($query);
		require_once $index_dir.'include/config/config_cleanup.php';
		if(mt_rand(1, floor(1/$cleanup_probability))==1) require $index_dir.'include/code/cleanup/code_block_alert_emails_history_expired_cleanup.php';
		if(mt_rand(1, floor(1/$cleanup_probability))==1) require $index_dir.'include/code/cleanup/code_block_alert_emails_history_size_cleanup.php';
	}
}

?>