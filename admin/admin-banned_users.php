<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
$parent_page=true;

$index_dir='../';

require_once '../include/common.php';

require '../include/code/code_encoding8anticache_headers.php';

require '../include/code/code_require_admin.php';

require '../include/code/code_pagination_params.php';

require_once '../include/code/code_db_object.php';

$query='select * from `accounts` where `banned`!=0';

if(!$total=$reg8log_db->result_num($query)) exit('<center><h3>No banned users found.</h3><a href="index.php">Admin operations</a><br><br><a href="../index.php">Login page</a></center>');

require '../include/code/code_pagination_params2.php';

$query='select * from `accounts` where `banned`!=0'." order by `$sort_by` $sort_dir, `auto` limit $per_page offset $offset";

$reg8log_db->query($query);

require '../include/page/page_banned_accounts.php';

?>
