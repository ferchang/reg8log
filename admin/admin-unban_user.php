<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
$parent_page=true;

$index_dir='../';

$store_request_entropy_probability2=1;

require_once $index_dir.'include/common.php';

require $index_dir.'include/code/code_encoding8anticache_headers.php';

require $index_dir.'include/code/admin/code_require_admin.php';

if(!empty($_POST)) require $index_dir.'include/code/code_prevent_xsrf.php';

//----------------------------

do {

if(!isset($_POST['ban_form1']) or isset($_POST['cancel'])) break;

if($_POST['user']==='') $err_msgs[]='user field is empty!';
if($_POST['which']!=='username' and $_POST['which']!=='uid') $err_msgs[]='which field value is incorrect!';

if(isset($err_msgs)) break;

$_POST['user']=str_replace(array('ي', 'ك'), array('ی', 'ک'), $_POST['user']);

require_once $index_dir.'include/code/code_db_object.php';
	
$user=$reg8log_db->quote_smart($_POST['user']);

$query="select * from `accounts` where `{$_POST['which']}`=$user limit 1";

if(!$reg8log_db->result_num($query)) {
	$err_msgs[]='no such user found in the accounts table!';
	break;
}

$rec=$reg8log_db->fetch_row();

if(strtolower($rec['username'])==='admin') {
	$err_msgs[]="Admin account cannot be banned!";
	break;
}

$ban_until=$rec['banned'];

$ban_reason ='';

if($rec['banned']) {
	$username=$reg8log_db->quote_smart($rec['username']);
	$query="select * from `ban_info` where `username`=$username limit 1";
	if(!$reg8log_db->result_num($query)) echo 'Warning: No corresponding ban_info record found for banned user!';
	else {
		$rec2=$reg8log_db->fetch_row();
		$ban_reason =$rec2['reason'];
	}
}

require $index_dir.'include/page/admin/page_unban_form.php';

exit;

} while(false);

//----------------------------

do {

if(!isset($_POST['unban_form']) or isset($_POST['cancel'])) break;

if(strtolower($_POST['username'])==='admin') {
	$err_msgs[]="Admin account cannot be banned!";
	break;
}

require $index_dir.'include/code/admin/code_unban_user.php';

} while(false);

require $index_dir.'include/page/admin/page_ban_form1.php';

?>
