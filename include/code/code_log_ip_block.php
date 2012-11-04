<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");



require_once $index_dir.'include/config/config_security_logs.php';

require_once $index_dir.'include/code/code_db_object.php';

$_username=$reg8log_db->quote_smart($_POST['username']);

$tmp29='insert into `ip_block_log` (`ip`, `last_attempt`, `last_username`) values ('.$ip.', '.$req_time.", $_username)";

$reg8log_db->query($tmp29);

if($alert_admin_about_ip_blocks) {
	if($alert_admin_about_ip_blocks==1) {
		$query="update `admin_alerts` set `new_ip_blocks`=`new_ip_blocks`+1 where `for`='visit' limit 1";
		$reg8log_db->query($query);
	}
	else if($alert_admin_about_ip_blocks==2) {
		require_once $index_dir.'include/code/code_fetch_site_vars.php';
		$lock_name3='reg8log--admin_ip_block_email_alert--'.$site_key;
		$reg8log_db->query("select get_lock('$lock_name3', -1)");
		$query="update `admin_alerts` set `new_ip_blocks`=`new_ip_blocks`+1 where `for`='email' limit 1";
		$reg8log_db->query($query);
		require $index_dir.'include/code/code_check_ip_blocks_admin_email_alert.php';
	}
	else {
		require_once $index_dir.'include/code/code_fetch_site_vars.php';
		$lock_name3='reg8log--admin_ip_block_email_alert--'.$site_key;
		$reg8log_db->query("select get_lock('$lock_name3', -1)");
		$query="update `admin_alerts` set `new_ip_blocks`=`new_ip_blocks`+1 limit 2";
		$reg8log_db->query($query);
		require $index_dir.'include/code/code_check_ip_blocks_admin_email_alert.php';
	}
}

require_once $index_dir.'include/config/config_cleanup.php';

if($keep_expired_block_log_records_for!=0 and mt_rand(1, floor(1/$cleanup_probability))==1) require $index_dir.'include/code/code_ip_block_log_expired_cleanup.php';

if(mt_rand(1, floor(1/$cleanup_probability))==1) require $index_dir.'include/code/code_ip_block_log_size_cleanup.php';


?>