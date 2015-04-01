<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$tmp38='select * from `ip_incorrect_logins` where `ip`='.$ip.' and `timestamp`>='.($req_time-config::get('ip_block_period')).' order by `timestamp` asc limit 1';

if($reg8log_db->result_num($tmp38)) {
	$tmp38=$reg8log_db->fetch_row();
	$ip_first_attempt=$tmp38['timestamp'];
}
else $ip_first_attempt=$req_time;

$_username=$reg8log_db->quote_smart($_POST['username']);

$tmp29='insert into `ip_block_log` (`ip`, `first_attempt`, `last_attempt`, `last_username`, `block_threshold`) values '."($ip, $ip_first_attempt, $req_time, $_username, ".config::get('ip_block_threshold').")";

$reg8log_db->query($tmp29);

if(config::get('alert_admin_about_ip_blocks')) {
	if(config::get('alert_admin_about_ip_blocks')===1) {
		$query="update `admin_block_alerts` set `new_ip_blocks`=`new_ip_blocks`+1 where `for`='visit' limit 1";
		$reg8log_db->query($query);
	}
	else if(config::get('alert_admin_about_ip_blocks')===2) {
		$lock_name3='reg8log--admin_ip_block_email_alert--'.$site_key;
		$reg8log_db->query("select get_lock('$lock_name3', -1)");
		$query="update `admin_block_alerts` set `new_ip_blocks`=`new_ip_blocks`+1 where `for`='email' limit 1";
		$reg8log_db->query($query);
		require ROOT.'include/code/admin/code_check_ip_blocks_admin_email_alert.php';
	}
	else {
		$lock_name3='reg8log--admin_ip_block_email_alert--'.$site_key;
		$reg8log_db->query("select get_lock('$lock_name3', -1)");
		$query="update `admin_block_alerts` set `new_ip_blocks`=`new_ip_blocks`+1 limit 2";
		$reg8log_db->query($query);
		require ROOT.'include/code/admin/code_check_ip_blocks_admin_email_alert.php';
	}
}

if(config::get('keep_expired_block_log_records_for')!==0 and mt_rand(1, floor(1/config::get('cleanup_probability')))===1) require ROOT.'include/code/cleanup/code_ip_block_log_expired_cleanup.php';

if(mt_rand(1, floor(1/config::get('cleanup_probability')))===1) require ROOT.'include/code/cleanup/code_ip_block_log_size_cleanup.php';

?>