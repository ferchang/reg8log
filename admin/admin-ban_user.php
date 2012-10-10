<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
$parent_page=true;

$index_dir='../';

$ban_page=true;

require_once '../include/common.php';

require '../include/code/code_encoding8anticache_headers.php';

require '../include/code/code_require_admin.php';

if(!empty($_POST)) require '../include/code/code_prevent_xsrf.php';

do {

if(!isset($_POST['ban_form1']) or isset($_POST['cancel'])) break;

if($_POST['user']==='') $err_msgs[]='user field is empty!';
if($_POST['which']!=='username' and $_POST['which']!=='uid') $err_msgs[]='which field value is incorrect!';

if(isset($err_msgs)) break;

$_POST['user']=str_replace(array('ي', 'ك'), array('ی', 'ک'), $_POST['user']);

require_once '../include/code/code_db_object.php';

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

require '../include/page/page_ban_form2.php';

exit;

} while(false);

do {

if(!isset($_POST['ban_form2']) or isset($_POST['cancel'])) break;

if(strtolower($_POST['username'])==='admin') {
	$err_msgs[]="Admin account cannot be banned!";
	break;
}

if(!$_POST['years'] and !$_POST['months'] and !$_POST['days'] and !$_POST['hours'] and $_POST['ban_type']!=='infinite') {//no ban duration

	$err_msgs[]='no ban duration specified!';

	require_once '../include/code/code_db_object.php';

	$query='select * from `accounts` where `username`='.$reg8log_db->quote_smart($_POST['username']).' limit 1';
	$reg8log_db->query($query);
	$rec=$reg8log_db->fetch_row();

	require '../include/page/page_ban_form2.php';
	
	exit;

}//no ban duration

require '../include/code/code_ban_user.php';

} while(false);

require '../include/page/page_ban_form1.php';

?>
