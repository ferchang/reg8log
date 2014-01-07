<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
$parent_page=true;

$index_dir='../';

$store_request_entropy_probability2=1;

require_once $index_dir.'include/common.php';

require $index_dir.'include/code/code_encoding8anticache_headers.php';

require $index_dir.'include/code/admin/code_require_admin.php';

if(!empty($_POST)) require $index_dir.'include/code/code_prevent_xsrf.php';

//##################################################################

do {//1

if(!isset($_POST['ban_form1']) or isset($_POST['cancel'])) break;

if($_POST['user']==='') $err_msgs[]=tr('user field is empty!');
if($_POST['which']!=='username' and $_POST['which']!=='uid') $err_msgs[]='which field value is incorrect!';

if(isset($err_msgs)) break;

require_once $index_dir.'include/func/func_yeh8kaaf.php';
fix_yeh8kaaf($_POST['user']);

require_once $index_dir.'include/code/code_db_object.php';
	
$user=$reg8log_db->quote_smart($_POST['user']);

$query="select * from `accounts` where `{$_POST['which']}`=$user limit 1";

if(!$reg8log_db->result_num($query)) {
	$err_msgs[]=tr('no such user found in the accounts table!');
	break;
}

$rec=$reg8log_db->fetch_row();

if($rec['username']==='Admin') {
	$err_msgs[]=tr('Admin account cannot be banned!');
	break;
}

$ban_until=$rec['banned'];

$ban_reason ='';

if($rec['banned']) {
	$username=$reg8log_db->quote_smart($rec['username']);
	$query="select * from `ban_info` where `username`=$username limit 1";
	if(!$reg8log_db->result_num($query)) echo tr('Info: No corresponding ban_info record found for the banned user!');
	else {
		$rec2=$reg8log_db->fetch_row();
		$ban_reason =$rec2['reason'];
	}
}
else {
	require $index_dir.'include/page/admin/page_unban_form.php';
	exit;
}

require $index_dir.'include/code/admin/code_check_password_entry_needed4admin.php';

require $index_dir.'include/page/admin/page_unban_form.php';

exit;

} while(false);//1

//##################################################################

do {//2

	if((!isset($_POST['unban_form']) and !isset($_POST['captcha'])) or isset($_POST['cancel'])) break;

	if(strtolower($_POST['username'])==='admin') {
		$err_msgs[]=tr('Admin account cannot be banned!');
		break;
	}
	
	require $index_dir.'include/code/admin/code_check_password_entry_needed4admin.php';

	require $index_dir.'include/code/admin/code_captcha8password_check.php';

	if(!isset($err_msgs)) {

		require $index_dir.'include/code/admin/code_update_password_check.php';

		require $index_dir.'include/code/admin/code_unban_user.php';
		
		exit;	

	}


	$user=$reg8log_db->quote_smart($_POST['username']);

	$query="select * from `accounts` where `username`=$user limit 1";

	if(!$reg8log_db->result_num($query)) {
		$err_msgs[]=tr('no such user found in the accounts table!');
		break;
	}

	$rec=$reg8log_db->fetch_row();

	if(strtolower($rec['username'])==='admin') {
		$err_msgs[]=tr('Admin account cannot be banned!');
		break;
	}

	$ban_until=$rec['banned'];

	$ban_reason ='';

	if($rec['banned']) {
		$username=$reg8log_db->quote_smart($rec['username']);
		$query="select * from `ban_info` where `username`=$username limit 1";
		if(!$reg8log_db->result_num($query)) echo tr('Info: No corresponding ban_info record found for the banned user!');
		else {
			$rec2=$reg8log_db->fetch_row();
			$ban_reason =$rec2['reason'];
		}
	}
	
	//-------------------

	require $index_dir.'include/page/admin/page_unban_form.php';

	exit;

} while(false);//2

//##################################################################

require $index_dir.'include/page/admin/page_ban_form1.php';

?>
