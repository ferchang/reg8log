<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(!empty($_POST)) {
	if(!isset($_COOKIE['reg8log_antixsrf_token'], $_POST['antixsrf_token']) or $_COOKIE['reg8log_antixsrf_token']!==$_POST['antixsrf_token']) {
		$failure_msg="<h3>XSRF prevention mechanism triggered!</h3>";
		$no_specialchars=true;
		require $index_dir.'include/page/page_failure.php';
		exit;
	}
}
else if(!isset($_COOKIE['reg8log_antixsrf_token'], $_GET['antixsrf_token']) or $_COOKIE['reg8log_antixsrf_token']!==$_GET['antixsrf_token']) {
	$failure_msg="<h3>XSRF prevention mechanism triggered!</h3>";
	$no_specialchars=true;
	require $index_dir.'include/page/page_failure.php';
	exit;
}

?>