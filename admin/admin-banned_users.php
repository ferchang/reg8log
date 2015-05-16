<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
define('CAN_INCLUDE', true);

$store_request_entropy_probability2=1;

require_once '../include/common.php';

require ROOT.'include/code/admin/code_require_admin.php';

$sort_fields=array('uid', 'username', 'email', 'gender', 'banned', 'timestamp', 'reason');
require ROOT.'include/code/admin/code_pagination_params.php';

$query='select * from `accounts` where `banned`=1 or `banned`>='.REQUEST_TIME;

if(!$total=$GLOBALS['reg8log_db']->result_num($query)) func::my_exit('<center><h3>'.func::tr('No banned users found.').'</h3><a href="index.php">'.func::tr('Admin operations').'</a><br><br><a href="../index.php">'.func::tr('Login page').'</a></center>');

require ROOT.'include/code/admin/code_pagination_params2.php';

$query='select accounts.*, ban_info.reason from `accounts` left join `ban_info` on accounts.username=ban_info.username where `banned`=1 or `banned`>='.REQUEST_TIME." order by `$sort_by` $sort_dir, `auto` limit $per_page offset $offset";

$GLOBALS['reg8log_db']->query($query);

require ROOT.'include/page/admin/page_banned_accounts.php';

?>
