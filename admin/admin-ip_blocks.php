<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
$parent_page=true;

$index_dir='../';

$store_request_entropy_probability2=1;

require_once $index_dir.'include/common.php';

require $index_dir.'include/code/code_encoding8anticache_headers.php';

require $index_dir.'include/code/code_require_admin.php';

$sort_fields=array('last_username', 'ip', 'first_attempt', 'last_attempt');
require $index_dir.'include/code/code_pagination_params.php';

require_once $index_dir.'include/code/code_db_object.php';

if(isset($_POST['admin_action'])) {

	require $index_dir.'include/code/code_prevent_xsrf.php';

	$i=0;
	foreach($_POST as $auto=>$action) {
		if($action=='del') $del[]=$auto;
		else if($action=='unblock') {
			$auto=substr($auto, 2);
			$unblock[$i]['ip']=pack('H*', $_POST['ip'.$auto]);
			$unblock[$i]['auto']=$auto;
			$unblock[$i]['last_attempt']=$_POST['t'.$auto];
			$i++;
		}
	}
	if(isset($unblock)) require $index_dir.'include/code/code_unblock_ips.php';
	if(isset($del)) require $index_dir.'include/code/code_delete_ip_block_log_records.php';
}

$query="select * from `ip_block_log`";

if(!$total=$reg8log_db->result_num($query)) {
	if(isset($queries_executed)) echo '<center style="color: #fff; background: green; padding: 3px; font-weight: bold; margin-bottom: 5px">Your command(s) executed.</center>';
	exit('<center><h3>No IP block log records found.</h3><a href="index.php">Admin operations</a><br><br><a href="../index.php">Login page</a></center>');
}

require $index_dir.'include/code/code_pagination_params2.php';

$query="select * from `ip_block_log` order by `$sort_by` $sort_dir, `auto` limit "."$per_page offset $offset";

$reg8log_db->query($query);

require $index_dir.'include/page/page_blocked_ips.php';

?>