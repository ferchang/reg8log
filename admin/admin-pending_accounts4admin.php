<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
define('CAN_INCLUDE', true);

$store_request_entropy_probability2=1;

require_once '../include/common.php';

require ROOT.'include/code/code_encoding8anticache_headers.php';

require ROOT.'include/code/code_set_site_salt.php';

require ROOT.'include/code/admin/code_require_admin.php';

$sort_fields=array('username', 'email', 'gender', 'emails_sent', 'email_verified', 'notify_user', 'timestamp');
require ROOT.'include/code/admin/code_pagination_params.php';

require_once ROOT.'include/code/code_db_object.php';

require ROOT.'include/code/admin/code_check_password_entry_needed4admin.php';

if(isset($_POST['admin_action']) or isset($_POST['captcha'])) {

	require ROOT.'include/code/code_prevent_xsrf.php';

	require ROOT.'include/code/admin/code_captcha8password_check.php';

	if(!isset($err_msgs)) {
		
		require ROOT.'include/code/admin/code_update_password_check.php';

		foreach($_POST as $auto=>$action) {
			if($action=='undet') continue;
			if($action=='del') $del[]=$auto;
			else if($action=='appr') $appr[]=$auto;
		}
		if(isset($appr)) require ROOT.'include/code/admin/code_approve_pending_accounts.php';
		if(isset($del)) require ROOT.'include/code/admin/code_delete_pending_accounts.php';
		
		//----------
		unset($password_check_needed, $captcha_needed);
		require ROOT.'include/code/admin/code_check_password_entry_needed4admin.php';
		//----------

	}
	
}

$expired1=$req_time-config::get('email_verification_time');
$expired2=$req_time-config::get('admin_confirmation_time');

$query="select * from `pending_accounts` where (`email_verification_key`='' or `email_verified`=1 or `timestamp`>=".$expired1.') and (`admin_confirmed`=0 and `timestamp`>='.$expired2.')';

if(!$total=$reg8log_db->result_num($query)) {
	if(isset($queries_executed)) echo '<center style="color: #fff; background: green; padding: 3px; font-weight: bold; margin-bottom: 5px">', func::tr('Your command(s) were executed.', true), '</center>';
	if(!empty($nonexistent_records)) echo '<center style="color: orange; background: #000; padding: 3px; font-weight: bold">', sprintf(func::tr('Info: %d record(s) did not exist.'), $nonexistent_records), '</center>';
	func::my_exit('<center><h3>'.func::tr('No pending accounts eligible for admin confirmation found.').'</h3><a href="index.php">'.func::tr('Admin operations').'</a><br><br><a href="../index.php">'.func::tr('Login page').'</a></center>');
}

require ROOT.'include/code/admin/code_pagination_params2.php';

$query="select * from `pending_accounts` where (`email_verification_key`='' or `email_verified`=1 or `timestamp`>=".$expired1.') and (`admin_confirmed`=0 and `timestamp`>='.$expired2.')'." order by `$sort_by` $sort_dir, `auto` limit $per_page offset $offset";

$reg8log_db->query($query);

require ROOT.'include/page/admin/page_pending_accounts4admin.php';

?>