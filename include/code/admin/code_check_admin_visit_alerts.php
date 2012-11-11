<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

require_once $index_dir.'include/config/config_security_logs.php';

if(!$alert_admin_about_account_blocks and !$alert_admin_about_ip_blocks) return;

if(isset($_COOKIE['reg8log_dont_disturb']) and $_COOKIE['reg8log_dont_disturb']==='1') return;

require_once $index_dir.'include/code/code_db_object.php';

$query="select * from `admin_alerts` where `for`='visit' limit 1";

$reg8log_db->query($query);

$rec=$reg8log_db->fetch_row();

$admin_alert_visit_msg='';

if($rec['new_account_blocks'] and in_array($alert_admin_about_account_blocks, array(1, 3, 4, 6))) {
	$new_account_blocks=$rec['new_account_blocks'];
	require $index_dir.'include/code/admin/code_check_account_blocks_alert_threshold.php';
	if(isset($account_blocks_alert_threshold_reached)) $admin_alert_visit_msg='- There were '.$new_account_blocks." new account block(s).\n";
}

if($rec['new_ip_blocks'] and in_array($alert_admin_about_ip_blocks, array(1, 3))) {
	$new_ip_blocks=$rec['new_ip_blocks'];
	require $index_dir.'include/code/admin/code_check_ip_blocks_alert_threshold.php';
	if(isset($ip_blocks_alert_threshold_reached)) $admin_alert_visit_msg.='- There were '.$new_ip_blocks.' new IP block(s).';
}

//----------------------------------------

require $index_dir.'include/config/config_register.php';

if($registerations_alert_type!=1 and $registerations_alert_type!=3) return;

$query="select * from `admin_reg_alerts` where `for`='visit' limit 1";

$reg8log_db->query($query);

$rec8=$reg8log_db->fetch_row();

$new_regs=$rec8['new_regs'];

if($new_regs<$registerations_alert_threshold) return;

if($admin_alert_visit_msg) $admin_alert_visit_msg.="\n\n";
$admin_alert_visit_msg.='- There were '.$new_regs.' new registeration(s).';

?>