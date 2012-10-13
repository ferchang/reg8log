<?php

if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
$parent_page=true;

$index_dir='';

require $index_dir.'include/common.php';

require $index_dir.'include/code/code_prevent_xsrf.php';

require_once $index_dir.'include/class/class_cookie.php';
require_once $index_dir.'include/class/class_user.php';
require $index_dir.'include/info/info_identify.php';

$user=new hm_user($identify_structs);
if($user->logout()) header('Location: index.php');
else {
	$failure_msg=($debug_mode)? $user->err_msg : 'Problem logging out';
	require $index_dir.'include/page/page_failure.php';
	exit;
}

?>
