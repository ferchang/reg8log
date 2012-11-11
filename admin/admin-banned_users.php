<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
$parent_page=true;

$index_dir='../';

$store_request_entropy_probability2=1;

require_once $index_dir.'include/common.php';

require $index_dir.'include/code/code_encoding8anticache_headers.php';

require $index_dir.'include/code/admin/code_require_admin.php';

$sort_fields=array('uid', 'username', 'email', 'gender', 'banned', 'timestamp', 'reason');
require $index_dir.'include/code/admin/code_pagination_params.php';

require_once $index_dir.'include/code/code_db_object.php';

$query='select * from `accounts` where `banned`=1 or `banned`>='.$req_time;

if(!$total=$reg8log_db->result_num($query)) exit('<center><h3>No banned users found.</h3><a href="index.php">Admin operations</a><br><br><a href="../index.php">Login page</a></center>');

require $index_dir.'include/code/admin/code_pagination_params2.php';

$query='select accounts.*, ban_info.reason from `accounts` left join `ban_info` on accounts.username=ban_info.username where `banned`=1 or `banned`>='.$req_time." order by `$sort_by` $sort_dir, `auto` limit $per_page offset $offset";

$reg8log_db->query($query);

require $index_dir.'include/page/admin/page_banned_accounts.php';

?>
