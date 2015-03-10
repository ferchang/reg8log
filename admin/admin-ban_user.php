<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
define('CAN_INCLUDE', true);



$ban_page=true;

$store_request_entropy_probability2=1;

require_once '../include/common.php';

require ROOT.'include/code/code_encoding8anticache_headers.php';

require ROOT.'include/code/admin/code_require_admin.php';

if(!empty($_POST)) require ROOT.'include/code/code_prevent_xsrf.php';

//##################################################################

do {//1

	if(!isset($_POST['ban_form1']) or isset($_POST['cancel'])) break;

	if($_POST['user']==='') $err_msgs[]=func::tr('user field is empty!');
	if($_POST['which']!=='username' and $_POST['which']!=='uid') $err_msgs[]='which field value is incorrect!';

	if(isset($err_msgs)) break;

	require_once ROOT.'include/func/func_yeh8kaaf.php';
	fix_yeh8kaaf($_POST['user']);

	require_once ROOT.'include/code/code_db_object.php';

	$user=$reg8log_db->quote_smart($_POST['user']);

	$query="select * from `accounts` where `{$_POST['which']}`=$user limit 1";

	if(!$reg8log_db->result_num($query)) {
		$err_msgs[]=func::tr('no such user found in the accounts table!');
		break;
	}

	$rec=$reg8log_db->fetch_row();

	if($rec['username']=='Admin') {
		$err_msgs[]=func::tr('Admin account cannot be banned!');
		break;
	}

	require ROOT.'include/code/admin/code_check_password_entry_needed4admin.php';

	require ROOT.'include/page/admin/page_ban_form2.php';

	exit;

} while(false);//1

//##################################################################

do {//2

	if((!isset($_POST['ban_form2']) and !isset($_POST['captcha'])) or isset($_POST['cancel'])) break;

	if(strtolower($_POST['username'])==='admin') {
		$err_msgs[]=func::tr('Admin account cannot be banned!');
		break;
	}

	if(!$_POST['years'] and !$_POST['months'] and !$_POST['days'] and !$_POST['hours'] and $_POST['ban_type']!=='infinite') $err_msgs[]=func::tr('no ban duration specified!');

	require ROOT.'include/code/admin/code_check_password_entry_needed4admin.php';

	require ROOT.'include/code/admin/code_captcha8password_check.php';

	if(!isset($err_msgs)) {

		require ROOT.'include/code/admin/code_update_password_check.php';

		require ROOT.'include/code/admin/code_ban_user.php';
		
		exit;	

	}
	
	$query='select * from `accounts` where `username`='.$reg8log_db->quote_smart($_POST['username']).' limit 1';
	$reg8log_db->query($query);
	$rec=$reg8log_db->fetch_row();
	require ROOT.'include/page/admin/page_ban_form2.php';
	exit;

} while(false);//2

//##################################################################

require ROOT.'include/page/admin/page_ban_form1.php';

?>
