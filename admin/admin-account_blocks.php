<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
$parent_page=true;

$index_dir='../';

$store_request_entropy_probability2=1;

require_once $index_dir.'include/common.php';

require $index_dir.'include/code/code_encoding8anticache_headers.php';

require $index_dir.'include/code/code_set_site_salt.php';

require $index_dir.'include/code/admin/code_require_admin.php';

$sort_fields=array('username', 'last_ip', 'first_attempt', 'last_attempt', 'username_exists');
require $index_dir.'include/code/admin/code_pagination_params.php';

require_once $index_dir.'include/code/code_db_object.php';

require $index_dir.'include/code/admin/code_check_password_entry_needed4admin.php';

if(isset($_POST['admin_action']) or isset($_POST['captcha'])) {

	require $index_dir.'include/code/code_prevent_xsrf.php';

	require $index_dir.'include/code/admin/code_captcha8password_check.php';
	
	if(!isset($err_msgs)) {
	
		require $index_dir.'include/code/admin/code_update_password_check.php';

		foreach($_POST as $auto=>$action) {
			if($action=='del') $del[]=$auto;
			else if($action=='unblock') $unblock[]=substr($auto, 3);
		}
	
		if(isset($unblock)) require $index_dir.'include/code/admin/code_unblock_accounts.php';
		if(isset($del)) require $index_dir.'include/code/admin/code_delete_account_block_log_records.php';

	}
	
}

$query="select * from `account_block_log`";

if(!$total=$reg8log_db->result_num($query)) {
	if(isset($queries_executed)) echo '<center style="color: #fff; background: green; padding: 3px; font-weight: bold; margin-bottom: 5px">', tr('Your command(s) were executed.', true), '</center>';
	my_exit('<center><h3>'.tr('No account block log records found.').'</h3><a href="index.php">'.tr('Admin operations').'</a><br><br><a href="../index.php">'.tr('Login page').'</a></center>');
}

require $index_dir.'include/code/admin/code_pagination_params2.php';

$query="select * from `account_block_log` order by `$sort_by` $sort_dir, `auto` limit "."$per_page offset $offset";

$reg8log_db->query($query);

require $index_dir.'include/page/admin/page_blocked_accounts.php';

?>