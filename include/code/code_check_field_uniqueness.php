<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

require_once $index_dir.'include/code/code_db_object.php';

require_once $index_dir.'include/code/code_fetch_site_vars.php';

$lock_name='reg8log--register--'.$site_key;
$reg8log_db->query("select get_lock('$lock_name', -1)");

$tmp8=$reg8log_db->quote_smart($field_value);
$query1='select * from `accounts` where `'.$field_name.'`='.$tmp8;
if(isset($except_user)) {
	$except_user=$reg8log_db->quote_smart($except_user);
	$query1.=' and `username`!='.$except_user;
}
$query1.=' limit 1';

require $index_dir.'include/config/config_register.php';

$expired1=time()-$email_verification_time;
$expired2=time()-$admin_confirmation_time;

$query2="select * from `pending_accounts` where `$field_name`=$tmp8  and (`email_verification_key`='' or `email_verified`=1 or `timestamp` > $expired1)  and (`admin_confirmed`=1 or `timestamp` > $expired2)";
if(isset($except_user)) $query2.=' and `username`!='.$except_user;
$query2.=' limit 1';

if($field_name=='username' and (!$ajax_check_username or $max_ajax_check_usernames)) {
	require_once $index_dir.'include/config/config_brute_force_protection.php';
	$expired=time()-$account_block_period;
	$query3="select * from `account_incorrect_logins` where `username`=$tmp8 and `last_attempt` > $expired limit 1";
}

unset($uniqueness_err);
if(
$reg8log_db->result_num($query1) or
$reg8log_db->result_num($query2) or
(isset($query3) and $reg8log_db->result_num($query3))
) {
	$err_msgs[]="$field_name is already registered; please choose another $field_name!";
	$uniqueness_err=true;
}

unset($query3);

?>
