<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
$parent_page=true;

$index_dir='../';

$store_request_entropy_probability2=1;

require_once $index_dir.'include/common.php';

require $index_dir.'include/code/code_encoding8anticache_headers.php';

require $index_dir.'include/code/code_set_site_salt.php';

require $index_dir.'include/code/admin/code_require_admin.php';

$sort_fields=array('username', 'email', 'gender', 'emails_sent', 'admin_confirmed', 'notify_user', 'timestamp');
require $index_dir.'include/code/admin/code_pagination_params.php';

require_once $index_dir.'include/code/code_db_object.php';

require $index_dir.'include/code/admin/code_check_password_entry_needed4admin.php';

require $index_dir.'include/config/config_register.php';

if(isset($_POST['admin_action']) or isset($_POST['captcha'])) {

	require $index_dir.'include/code/code_prevent_xsrf.php';

	require $index_dir.'include/code/admin/code_captcha8password_check.php';
	
	if(!isset($err_msgs)) {
	
		require $index_dir.'include/code/admin/code_update_password_check.php';

		foreach($_POST as $auto=>$action) {
			if($action=='undet') continue;
			if($action=='del') $del[]=$auto;
		}
		if(isset($del)) require $index_dir.'include/code/admin/code_delete_pending_accounts.php';

		//----------
		unset($password_check_needed, $captcha_needed);
		require $index_dir.'include/code/admin/code_check_password_entry_needed4admin.php';
		//----------
		
	}

}

require $index_dir.'include/config/config_register.php';

$expired1=$req_time-$email_verification_time;
$expired2=$req_time-$admin_confirmation_time;

$query="select * from `pending_accounts` where (`email_verification_key`!='' and `email_verified`=0 and `timestamp`>=".$expired1.') and (`admin_confirmed`=1 or `timestamp`>='.$expired2.')';

if(!$total=$reg8log_db->result_num($query)) {
	if(isset($queries_executed)) echo '<center style="color: #fff; background: green; padding: 3px; font-weight: bold; margin-bottom: 5px">', tr('Your command(s) were executed.', true), '</center>';
	if(!empty($nonexistent_records)) echo '<center style="color: orange; background: #000; padding: 3px; font-weight: bold">', sprintf(tr('Info: %d record(s) did not exist.'), $nonexistent_records), '</center>';
	my_exit('<center><h3>'.tr('No pending accounts awaiting for email verification found.').'</h3><a href="index.php">'.tr('Admin operations').'</a><br><br><a href="../index.php">'.tr('Login page').'</a></center>');
}

require $index_dir.'include/code/admin/code_pagination_params2.php';

$query="select * from `pending_accounts` where (`email_verification_key`!='' and `email_verified`=0 and `timestamp`>=".$expired1.') and (`admin_confirmed`=1 or `timestamp`>='.$expired2.')'." order by `$sort_by` $sort_dir, `auto` limit $per_page offset $offset";

$reg8log_db->query($query);

require $index_dir.'include/page/admin/page_pending_accounts4email.php';

?>