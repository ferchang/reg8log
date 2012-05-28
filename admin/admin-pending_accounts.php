<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
$parent_page=true;

$index_dir='../';

$store_request_entropy_probability2=1;

require_once '../include/common.php';

require '../include/code/code_encoding8anticache_headers.php';

$page=(isset($_GET['page']) and is_numeric($_GET['page']))? floor($_GET['page']) : 1;

if(isset($_POST['change_per_page']) and isset($_POST['per_page']) and is_numeric($_POST['per_page'])) {
	$per_page=floor($_POST['per_page']);
	header("Location: admin-pending_accounts.php?per_page=$per_page&page=$page");
	exit;
}

$per_pages=array(10, 15, 20, 30, 40, 50, 70, 100, 150, 200, 300);

$per_page=(isset($_GET['per_page']) and is_numeric($_GET['per_page']))? floor($_GET['per_page']) : $per_pages[0];

if($page<=0 or $per_page<=0) exit('<center><h3>Error: page and/or per_page parameter is negative!</center>');

require '../include/code/code_identify.php';

if($identified_username!=='Admin') exit('<center><h3>You are not authenticated as Admin!<br>First log in as Admin.</h3><a href="../index.php">Login page</a></center>');

require_once '../include/code/code_db_object.php';

require '../include/info/info_register.php';

if(!empty($_POST)) {

	require '../include/code/code_prevent_xsrf.php';

	foreach($_POST as $auto=>$action) {
		if($action=='undet') continue;
		if($action=='del') $del[]=$auto;
		else if($action=='appr') $appr[]=$auto;
	}
	if(isset($appr)) require '../include/code/code_approve_pending_accounts.php';
	if(isset($del)) require '../include/code/code_delete_pending_accounts.php';
}

require '../include/info/info_register.php';

$expired1=time()-$email_verification_time;
$expired2=time()-$admin_confirmation_time;

$query="select * from `pending_accounts` where (`email_verification_key`='' or `email_verified`=1 or `timestamp`>=".$expired1.') and (`admin_confirmed`=0 and `timestamp`>='.$expired2.') order by `timestamp` desc';

if(!$total=$reg8log_db->result_num($query)) {
	if(isset($queries_executed)) echo '<center style="color: #fff; background: green; padding: 3px; font-weight: bold; margin-bottom: 5px">Your command(s) executed.</center>';
	if(!empty($nonexistent_records)) echo '<center style="color: orange; background: #000; padding: 3px; font-weight: bold">Info: ', $nonexistent_records, ' record(s) did not exist.</center>';
	exit('<center><h3>No pending accounts eligible for admin confirmation (Query returned 0 rows).</h3><a href="admin_operations.php">Admin operations</a><br><br><a href="../index.php">Login page</a></center>');
}

if(($page-1)*$per_page>=$total) {
	$tmp4=floor($total/$per_page);
	if($tmp4*$per_page<$total) $page=$tmp4+1;
	else $page=$tmp4;
	header("Location: admin-pending_accounts.php?per_page=$per_page&page=$page");
	exit;
}

$offset=($page-1)*$per_page;

$query="select * from `pending_accounts` where (`email_verification_key`='' or `email_verified`=1 or `timestamp`>=".$expired1.') and (`admin_confirmed`=0 and `timestamp`>='.$expired2.') order by `timestamp` desc limit '."$per_page offset $offset";

$reg8log_db->query($query);

require '../include/page/page_pending_accounts.php';

?>