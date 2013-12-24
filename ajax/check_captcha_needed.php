<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
$parent_page=true;

$index_dir='../';

require_once $index_dir.'include/common.php';

require $index_dir.'include/code/code_encoding8anticache_headers.php';

require $index_dir.'include/code/code_prevent_xsrf.php';

if(!isset($_POST['username'])) {
	$failure_msg="No username specified";
	require $index_dir.'include/page/page_failure.php';
	exit;
}

require_once $index_dir.'include/func/func_yeh8kaaf.php';
fix_yeh8kaaf($_POST['username']);

require $index_dir.'include/config/config_brute_force_protection.php';

$_username=$_POST['username'];
require $index_dir.'include/code/code_check_ip_block.php';
if(!isset($captcha_needed)) require $index_dir.'include/code/code_check_account_block.php';

if(isset($captcha_needed)) {
	echo '<!-- *add captcha from* -->';
	require $index_dir.'include/page/page_captcha_form.php';
}
else echo 'n';

?>