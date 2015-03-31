<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
define('CAN_INCLUDE', true);

$store_request_entropy_probability2=1;

require_once '../include/common.php';

require ROOT.'include/code/admin/code_require_admin.php';

$sort_fields=array('table_name', 'num_records');

if(isset($_GET['sort_by']) and in_array($_GET['sort_by'], $sort_fields))  $sort_by=$_GET['sort_by'];
else $sort_by='auto';

if(isset($_GET['sort_dir']) and ($_GET['sort_dir']=='asc' or $_GET['sort_dir']=='desc')) $sort_dir=$_GET['sort_dir'];
else $sort_dir='desc';

require_once ROOT.'include/page/admin/page_tables_status.php';

?>