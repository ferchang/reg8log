<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

require_once $index_dir.'include/info/info_security_logs.php';

require_once $index_dir.'include/code/code_db_object.php';

require_once $index_dir.'include/func/func_inet.php';

$ip=$reg8log_db->quote_smart(inet_pton2($_SERVER['REMOTE_ADDR']));

$query='insert into `account_lockdown_log` (`ext_auto`, `username`, `last_attempt`, `ip`) values ('."$insert_id, $_username, $req_time, $ip)";

$reg8log_db->query($query);

if($exempt_admin_account_from_alert_limits and strtolower($_POST['username'])=='admin') $no_alert_limits=true;
else $no_alert_limits=false;

if($alert_admin_about_account_blocks and !($alert_admin_about_account_blocks>3 and !$no_alert_limits)) {
	if(in_array($alert_admin_about_account_blocks, array(1, 4))) {
		$query="update `admin_alerts` set `new_account_blocks`=`new_account_blocks`+1 where `for`='visit' limit 1";
		$reg8log_db->query($query);
	}
	else if(in_array($alert_admin_about_account_blocks, array(2, 5))) {
		require_once $index_dir.'include/code/code_fetch_site_vars.php';
		$lock_name2='reg8log--admin_account_block_email_alert--'.$site_key;
		$reg8log_db->query("select get_lock('$lock_name2', -1)");
		$query="update `admin_alerts` set `new_account_blocks`=`new_account_blocks`+1 where `for`='email' limit 1";
		$reg8log_db->query($query);
		require $index_dir.'include/code/code_check_account_blocks_admin_email_alert.php';
	}
	else {
		require_once $index_dir.'include/code/code_fetch_site_vars.php';
		$lock_name2='reg8log--admin_account_block_email_alert--'.$site_key;
		$reg8log_db->query("select get_lock('$lock_name2', -1)");
		$query="update `admin_alerts` set `new_account_blocks`=`new_account_blocks`+1 limit 2";
		$reg8log_db->query($query);
		require $index_dir.'include/code/code_check_account_blocks_admin_email_alert.php';
	}
}

require_once $index_dir.'include/info/info_cleanup.php';

if($keep_expired_block_log_records_for!=0 and mt_rand(1, floor(1/$cleanup_probability))==1) require $index_dir.'include/code/code_account_lockdown_log_expired_cleanup.php';

if(mt_rand(1, floor(1/$cleanup_probability))==1) require $index_dir.'include/code/code_account_lockdown_log_size_cleanup.php';

?>