<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");



$max_page_links=20;

$per_pages=array(10, 15, 20, 30, 40, 50, 70, 100, 150, 200, 300);

$per_page=(isset($_GET['per_page']) and is_numeric($_GET['per_page']))? $_GET['per_page'] : $per_pages[0];

if(isset($_GET['sort_by']) and in_array($_GET['sort_by'], $sort_fields))  $sort_by=$_GET['sort_by'];
else $sort_by='auto';

if(isset($_GET['sort_dir']) and ($_GET['sort_dir']=='asc' or $_GET['sort_dir']=='desc')) $sort_dir=$_GET['sort_dir'];
else $sort_dir='asc';

$page=(isset($_GET['page']) and is_numeric($_GET['page']))? $_GET['page'] : 1;

if(isset($_POST['change_per_page']) and isset($_POST['per_page']) and is_numeric($_POST['per_page'])) {
	$per_page=$_POST['per_page'];
	header("Location: ?per_page=$per_page&page=$page&sort_by=$sort_by&sort_dir=$sort_dir");
	exit;
}

if(isset($_POST['goto']) and isset($_POST['page']) and is_numeric($_POST['page'])) {
	if($_POST['page']<1) $page='1';
	else $page=ceil($_POST['page']);
	header("Location: ?per_page=$per_page&page=$page&sort_by=$sort_by&sort_dir=$sort_dir");
	exit;
}

?>