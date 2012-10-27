<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
$parent_page=true;

$index_dir='../';

require_once $index_dir.'include/common.php';

require $index_dir.'include/code/code_encoding8anticache_headers.php';

require $index_dir.'include/code/code_require_admin.php';

require_once $index_dir.'include/code/code_db_object.php';

require_once $index_dir.'include/code/code_db_object.php';

$sort_fields=array('table_name', 'num_records');

if(isset($_GET['sort_by']) and in_array($_GET['sort_by'], $sort_fields))  $sort_by=$_GET['sort_by'];
else $sort_by='auto';

if(isset($_GET['sort_dir']) and ($_GET['sort_dir']=='asc' or $_GET['sort_dir']=='desc')) $sort_dir=$_GET['sort_dir'];
else $sort_dir='desc';

require_once $index_dir.'include/page/page_tables_status.php';

?>