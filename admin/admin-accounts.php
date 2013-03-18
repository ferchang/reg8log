<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
$parent_page=true;

$index_dir='../';

$store_request_entropy_probability2=1;

require_once $index_dir.'include/common.php';

require $index_dir.'include/code/code_encoding8anticache_headers.php';

require $index_dir.'include/code/admin/code_require_admin.php';

$sort_fields=array('uid', 'username', 'email', 'gender', 'banned', 'timestamp', 'last_activity', 'last_logout', 'last_login');
require $index_dir.'include/code/admin/code_pagination_params.php';

require_once $index_dir.'include/code/code_db_object.php';

if(isset($_POST['delete'])) {

	require $index_dir.'include/code/code_prevent_xsrf.php';

	foreach($_POST as $auto=>$action) if($action=='del') $del[]=$auto;
	
	if(isset($del)) require $index_dir.'include/code/admin/code_delete_accounts.php';

}

$query="select * from `accounts` where `username`!='Admin'";

if(!$total=$reg8log_db->result_num($query)) {
	if(isset($queries_executed)) echo '<center style="color: #fff; background: green; padding: 3px; font-weight: bold; margin-bottom: 5px">', tr('Your command(s) were executed.', true), '</center>';
	my_exit('<center><h3>'.tr('No accounts found.').'</h3><a href="index.php">'.tr('Admin operations').'</a><br><br><a href="../index.php">'.tr('Login page').'</a></center>');
}

require $index_dir.'include/code/admin/code_pagination_params2.php';

$query="select * from `accounts` where `username`!='Admin' order by `$sort_by` $sort_dir, `auto` limit "."$per_page offset $offset";

$reg8log_db->query($query);

require $index_dir.'include/page/admin/page_accounts.php';

?>