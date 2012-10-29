<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
$parent_page=true;

$index_dir='../';

require_once $index_dir.'include/common.php';

require $index_dir.'include/code/code_encoding8anticache_headers.php';

require $index_dir.'include/config/config_register.php';

if(!$ajax_check_username or !$registeration_enabled) exit('ajax username check or registeration is disabled!');

if(!isset($_GET['value'])) {
	$failure_msg="No value specified";
	require $index_dir.'include/page/page_failure.php';
	exit;
}

$value=$_GET['value'];

require_once $index_dir.'include/code/code_db_object.php';

require $index_dir.'include/code/code_check_max_ajax_check_usernames.php';

require $index_dir.'include/code/code_record_ajax_check_username.php';

$value=$reg8log_db->quote_smart($value);

$query1="select * from `accounts` where `username`=$value limit 1";

$expired1=time()-$email_verification_time;
$expired2=time()-$admin_confirmation_time;

$query2="select * from `pending_accounts` where `username`=$value and (`email_verification_key`='' or `email_verified`=1 or `timestamp` >= $expired1) and (`admin_confirmed`=1 or `timestamp` >= $expired2) limit 1";

if($reg8log_db->result_num($query1) or $reg8log_db->result_num($query2)) echo 'y';
else echo 'n';

?>