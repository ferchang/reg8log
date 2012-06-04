<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
$parent_page=true;

$index_dir='../';

require_once '../include/common.php';

require '../include/code/code_encoding8anticache_headers.php';

require '../include/code/code_require_admin.php';

require '../include/code/code_pagination_params.php';

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

require '../include/code/code_pagination_params2.php';

$query="select * from `accounts` where `username`!='Admin' order by `$sort_by` $sort_dir, `auto` limit "."$per_page offset $offset";

$reg8log_db->query($query);

require '../include/page/page_accounts.php';

?>