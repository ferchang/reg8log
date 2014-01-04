<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
$parent_page=true;

$index_dir='../';

$ban_page=true;

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

	if($rec['username']=='Admin') {
		$err_msgs[]=tr('Admin account cannot be banned!');
		break;
	}

	//-------------

	require_once $index_dir.'include/config/config_admin.php';

	require $index_dir.'include/code/admin/code_check_password_entry_needed4admin.php';

	if(isset($password_check_needed)) {
		$try_type='password';
		require $index_dir.'include/code/code_check_captcha_needed4user.php';

		if(isset($captcha_needed)) {
			require $index_dir.'include/code/sess/code_sess_start.php';
			$captcha_verified=isset($_SESSION['captcha_verified']);
		}
	}

	//-------------

	require $index_dir.'include/page/admin/page_ban_form2.php';

	exit;

} while(false);//1

//##################################################################

do {//2

	if((!isset($_POST['ban_form2']) and !isset($_POST['captcha'])) or isset($_POST['cancel'])) break;

	if(strtolower($_POST['username'])==='admin') {
		$err_msgs[]=tr('Admin account cannot be banned!');
		break;
	}

	if(!$_POST['years'] and !$_POST['months'] and !$_POST['days'] and !$_POST['hours'] and $_POST['ban_type']!=='infinite') $err_msgs[]=tr('no ban duration specified!');

	//-------------

	require_once $index_dir.'include/config/config_admin.php';

	require $index_dir.'include/code/admin/code_check_password_entry_needed4admin.php';

	if(isset($password_check_needed)) {
		$try_type='password';
		require $index_dir.'include/code/code_check_captcha_needed4user.php';

		if(isset($captcha_needed)) {
			require $index_dir.'include/code/sess/code_sess_start.php';
			$captcha_verified=isset($_SESSION['captcha_verified']);
		}
	}

	//-------------

	require $index_dir.'include/code/admin/code_captcha8password_check.php';

	if(!isset($err_msgs)) {

		require $index_dir.'include/code/admin/code_update_password_check.php';

		require $index_dir.'include/code/admin/code_ban_user.php';
		
		exit;	

	}
	
	$query='select * from `accounts` where `username`='.$reg8log_db->quote_smart($_POST['username']).' limit 1';
	$reg8log_db->query($query);
	$rec=$reg8log_db->fetch_row();
	require $index_dir.'include/page/admin/page_ban_form2.php';
	exit;

} while(false);//2

//##################################################################

require $index_dir.'include/page/admin/page_ban_form1.php';

?>
