<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(!empty($_POST)) {
	if(!isset($_POST['antixsrf_token']) or ANTIXSRF_TOKEN4POST!==$_POST['antixsrf_token']) {
		$failure_msg='<h3>'.func::tr('XSRF prevention mechanism triggered!').'</h3>';
		$no_specialchars=true;
		require ROOT.'include/page/page_failure.php';
		exit;
	}
}
else if(!isset($_GET['antixsrf_token']) or ANTIXSRF_TOKEN4GET!==$_GET['antixsrf_token']) {
	$failure_msg='<h3>'.func::tr('XSRF prevention mechanism triggered!').'</h3>';
	$no_specialchars=true;
	require ROOT.'include/page/page_failure.php';
	exit;
}

?>