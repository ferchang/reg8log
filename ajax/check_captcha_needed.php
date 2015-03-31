<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
define('CAN_INCLUDE', true);

require_once '../include/common.php';

require ROOT.'include/code/code_prevent_xsrf.php';

if(!isset($_POST['username'])) {
	$failure_msg="No username specified";
	require ROOT.'include/page/page_failure.php';
	exit;
}

$_POST['username']=func::fix_kaaf8yeh($_POST['username']);

$_username=$_POST['username'];
require ROOT.'include/code/code_check_ip_block.php';
if(!isset($captcha_needed)) require ROOT.'include/code/code_check_account_block.php';

if(isset($captcha_needed)) {
	echo '<!-- *add captcha from* -->';
	$ajax=true;
	require ROOT.'include/page/page_captcha_form.php';
}
else echo 'n';

?>