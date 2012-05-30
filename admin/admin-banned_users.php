<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
$parent_page=true;

$index_dir='../';

require_once '../include/common.php';

require '../include/code/code_encoding8anticache_headers.php';

if(!empty($_POST)) {
	require '../include/code/code_prevent_repost.php';
	require '../include/code/code_prevent_xsrf.php';
}

$page=(isset($_GET['page']) and is_numeric($_GET['page']))? floor($_GET['page']) : 1;

if(isset($_POST['change_per_page']) and isset($_POST['per_page']) and is_numeric($_POST['per_page'])) {
	$per_page=floor($_POST['per_page']);
	header("Location: admin-banned_users.php?per_page=$per_page&page=$page");
	exit;
}

$per_pages=array(10, 15, 20, 30, 40, 50, 70, 100, 150, 200, 300);

$per_page=(isset($_GET['per_page']) and is_numeric($_GET['per_page']))? floor($_GET['per_page']) : $per_pages[0];

if($page<=0 or $per_page<=0) exit('<center><h3>Error: page and/or per_page parameter is negative!</center>');

require '../include/code/code_identify.php';

if($identified_username!=='Admin') exit('<center><h3>You are not authenticated as Admin!<br>First log in as Admin.</h3><a href="../index.php">Login page</a></center>');

//=======================

require_once '../include/code/code_db_object.php';

$query='select * from `accounts` where `banned`!=0';

if(!$total=$reg8log_db->result_num($query)) exit('<center><h3>No banned users found.</h3><a href="index.php">Admin operations</a><br><br><a href="../index.php">Login page</a></center>');

if(($page-1)*$per_page>=$total) {
	$tmp3=floor($total/$per_page);
	if($tmp3*$per_page<$total) $page=$tmp3+1;
	else $page=$tmp3;
	header("Location: admin-banned_users.php?per_page=$per_page&page=$page");
	exit;
}

$offset=($page-1)*$per_page;

$query='select * from `accounts` where `banned`!=0 order by `username` limit '."$per_page offset $offset";

$reg8log_db->query($query);

require '../include/page/page_banned_accounts.php';

?>
