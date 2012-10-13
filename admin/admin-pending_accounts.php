<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
$parent_page=true;

$index_dir='../';

$store_request_entropy_probability2=1;

require_once $index_dir.'include/common.php';

require $index_dir.'include/code/code_encoding8anticache_headers.php';

require $index_dir.'include/code/code_require_admin.php';

$sort_fields=array('username', 'email', 'gender', 'emails_sent', 'email_verified', 'notify_user', 'timestamp');
require $index_dir.'include/code/code_pagination_params.php';

require_once $index_dir.'include/code/code_db_object.php';

require $index_dir.'include/info/info_register.php';

if(isset($_POST['admin_action'])) {

	require $index_dir.'include/code/code_prevent_xsrf.php';

	foreach($_POST as $auto=>$action) {
		if($action=='undet') continue;
		if($action=='del') $del[]=$auto;
		else if($action=='appr') $appr[]=$auto;
	}
	if(isset($appr)) require $index_dir.'include/code/code_approve_pending_accounts.php';
	if(isset($del)) require $index_dir.'include/code/code_delete_pending_accounts.php';
}

require $index_dir.'include/info/info_register.php';

$expired1=time()-$email_verification_time;
$expired2=time()-$admin_confirmation_time;

$query="select * from `pending_accounts` where (`email_verification_key`='' or `email_verified`=1 or `timestamp`>=".$expired1.') and (`admin_confirmed`=0 and `timestamp`>='.$expired2.')';

if(!$total=$reg8log_db->result_num($query)) {
	if(isset($queries_executed)) echo '<center style="color: #fff; background: green; padding: 3px; font-weight: bold; margin-bottom: 5px">Your command(s) executed.</center>';
	if(!empty($nonexistent_records)) echo '<center style="color: orange; background: #000; padding: 3px; font-weight: bold">Info: ', $nonexistent_records, ' record(s) did not exist.</center>';
	exit('<center><h3>No pending accounts eligible for admin confirmation found.</h3><a href="index.php">Admin operations</a><br><br><a href="../index.php">Login page</a></center>');
}

require $index_dir.'include/code/code_pagination_params2.php';

$query="select * from `pending_accounts` where (`email_verification_key`='' or `email_verified`=1 or `timestamp`>=".$expired1.') and (`admin_confirmed`=0 and `timestamp`>='.$expired2.')'." order by `$sort_by` $sort_dir, `auto` limit $per_page offset $offset";

$reg8log_db->query($query);

require $index_dir.'include/page/page_pending_accounts.php';

?>