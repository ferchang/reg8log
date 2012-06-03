<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
$parent_page=true;

$index_dir='../';

require_once '../include/common.php';

require '../include/code/code_encoding8anticache_headers.php';

if(isset($_GET['sort_by']) and in_array($_GET['sort_by'], array('uid', 'auto', 'username', 'email', 'gender', 'banned')))  $sort_by=$_GET['sort_by'];
else $sort_by='auto';

if(isset($_GET['sort_dir']) and ($_GET['sort_dir']=='asc' or $_GET['sort_dir']=='desc')) $sort_dir=$_GET['sort_dir'];
else $sort_dir='asc';

$page=(isset($_GET['page']) and is_numeric($_GET['page']))? $_GET['page'] : 1;

if(isset($_POST['change_per_page']) and isset($_POST['per_page']) and is_numeric($_POST['per_page'])) {
	$per_page=$_POST['per_page'];
	header("Location: admin-accounts.php?per_page=$per_page&page=$page&sort_by=$sort_by&sort_dir=$sort_dir");
	exit;
}

if(isset($_POST['goto']) and isset($_POST['page']) and is_numeric($_POST['page'])) {
	if($_POST['page']<1) $page='1';
	else $page=$_POST['page'];
	$page=ceil($page);
	if(isset($_GET['per_page']) and is_numeric($_GET['per_page'])) {
		$per_page=$_GET['per_page'];
		header("Location: admin-accounts.php?per_page=$per_page&page=$page&sort_by=$sort_by&sort_dir=$sort_dir");
	}
	else header("Location: admin-accounts.php?page=$page&sort_by=$sort_by&sort_dir=$sort_dir");
	exit;
}

$max_page_links=20;

$per_pages=array(10, 15, 20, 30, 40, 50, 70, 100, 150, 200, 300);

$per_page=(isset($_GET['per_page']) and is_numeric($_GET['per_page']))? $_GET['per_page'] : $per_pages[0];

require '../include/code/code_identify.php';

if($identified_username!=='Admin') exit('<center><h3>You are not authenticated as Admin!<br>First log in as Admin.</h3><a href="../index.php">Login page</a></center>');

require_once '../include/code/code_db_object.php';

if(isset($_POST['delete'])) {

	require '../include/code/code_prevent_xsrf.php';

	foreach($_POST as $auto=>$action) if($action=='del') $del[]=$auto;
	
	if(isset($del)) require '../include/code/code_delete_accounts.php';

}

$query="select * from `accounts` where `username`!='Admin'";

if(!$total=$reg8log_db->result_num($query)) {
	exit('<center><h3>No accounts (Query returned 0 rows).</h3><a href="index.php">Admin operations</a><br><br><a href="../index.php">Login page</a></center>');
}

if(($page-1)*$per_page>=$total) {
	$tmp27=floor($total/$per_page);
	if($tmp27*$per_page<$total) $page=$tmp27+1;
	else $page=$tmp27;
	header("Location: admin-accounts.php?per_page=$per_page&page=$page&sort_by=$sort_by&sort_dir=$sort_dir");
	exit;
}

$offset=($page-1)*$per_page;

$query="select * from `accounts` where `username`!='Admin' order by `$sort_by` $sort_dir limit "."$per_page offset $offset";

$reg8log_db->query($query);

require '../include/page/page_accounts.php';

?>