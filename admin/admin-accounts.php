<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
define('CAN_INCLUDE', true);

$store_request_entropy_probability2=1;

require_once '../include/common.php';

require ROOT.'include/code/admin/code_require_admin.php';

$sort_fields=array('uid', 'username', 'email', 'gender', 'banned', 'timestamp', 'last_activity', 'last_logout', 'last_login');
require ROOT.'include/code/admin/code_pagination_params.php';

require ROOT.'include/code/admin/code_check_password_entry_needed4admin.php';

if(isset($_POST['delete']) or isset($_POST['captcha'])) {

	require ROOT.'include/code/code_prevent_xsrf.php';

	require ROOT.'include/code/admin/code_captcha8password_check.php';
	
	if(!isset($err_msgs)) {
	
		require ROOT.'include/code/admin/code_update_password_check.php';
		
		foreach($_POST as $auto=>$action) if($action=='del') $del[]=$auto;
		if(isset($del)) require ROOT.'include/code/admin/code_delete_accounts.php';
		
		//----------
		unset($password_check_needed, $captcha_needed);
		require ROOT.'include/code/admin/code_check_password_entry_needed4admin.php';
		//----------

	}

}

$query="select * from `accounts` where `username`!='Admin'";

if(!$total=$reg8log_db->result_num($query)) {
	if(isset($queries_executed)) echo '<center style="color: #fff; background: green; padding: 3px; font-weight: bold; margin-bottom: 5px">', func::tr('Your command(s) were executed.', true), '</center>';
	func::my_exit('<center><h3>'.func::tr('No accounts found.').'</h3><a href="index.php">'.func::tr('Admin operations').'</a><br><br><a href="../index.php">'.func::tr('Login page').'</a></center>');
}

require ROOT.'include/code/admin/code_pagination_params2.php';

$query="select * from `accounts` where `username`!='Admin' order by `$sort_by` $sort_dir, `auto` limit "."$per_page offset $offset";

$reg8log_db->query($query);

require ROOT.'include/page/admin/page_accounts.php';

?>