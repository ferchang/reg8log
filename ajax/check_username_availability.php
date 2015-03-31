<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
define('CAN_INCLUDE', true);

require_once '../include/common.php';

require ROOT.'include/code/code_prevent_xsrf.php';

if(!config::get('ajax_check_username') or !config::get('registeration_enabled')) exit('ajax username check or registeration is disabled!');

if(!isset($_POST['value'])) {
	$failure_msg="No value specified";
	require ROOT.'include/page/page_failure.php';
	exit;
}

$_POST['value']=func::fix_kaaf8yeh($_POST['value']);

$value=$_POST['value'];

require ROOT.'include/code/code_check_max_ajax_check_usernames.php';

require ROOT.'include/code/code_record_ajax_check_username.php';

$value=$reg8log_db->quote_smart($value);

$query1="select * from `accounts` where `username`=$value limit 1";

$expired1=$req_time-config::get('email_verification_time');
$expired2=$req_time-config::get('admin_confirmation_time');

$query2="select * from `pending_accounts` where `username`=$value and (`email_verification_key`='' or `email_verified`=1 or `timestamp` >= $expired1) and (`admin_confirmed`=1 or `timestamp` >= $expired2) limit 1";

if($reg8log_db->result_num($query1) or $reg8log_db->result_num($query2)) echo 'y';
else echo 'n';

?>