<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
define('CAN_INCLUDE', true);

$store_request_entropy_probability2=1;

require_once '../include/common.php';

require ROOT.'include/code/admin/code_require_admin.php';

if(!empty($_POST)) require ROOT.'include/code/code_prevent_xsrf.php';

//##################################################################

do {//1

if(!isset($_POST['ban_form1']) or isset($_POST['cancel'])) break;

if($_POST['user']==='') $err_msgs[]=func::tr('user field is empty!');
if($_POST['which']!=='username' and $_POST['which']!=='uid') $err_msgs[]='which field value is incorrect!';

if(isset($err_msgs)) break;

$_POST['user']=func::fix_kaaf8yeh($_POST['user']);

$user=$GLOBALS['reg8log_db']->quote_smart($_POST['user']);

$query="select * from `accounts` where `{$_POST['which']}`=$user limit 1";

if(!$GLOBALS['reg8log_db']->result_num($query)) {
	$err_msgs[]=func::tr('no such user found in the accounts table!');
	break;
}

$rec=$GLOBALS['reg8log_db']->fetch_row();

if($rec['username']==='Admin') {
	$err_msgs[]=func::tr('Admin account cannot be banned!');
	break;
}

$ban_until=$rec['banned'];

$ban_reason ='';

if($rec['banned']) {
	$username=$GLOBALS['reg8log_db']->quote_smart($rec['username']);
	$query="select * from `ban_info` where `username`=$username limit 1";
	if(!$GLOBALS['reg8log_db']->result_num($query)) echo func::tr('Info: No corresponding ban_info record found for the banned user!');
	else {
		$rec2=$GLOBALS['reg8log_db']->fetch_row();
		$ban_reason =$rec2['reason'];
	}
}
else {
	require ROOT.'include/page/admin/page_unban_form.php';
	exit;
}

require ROOT.'include/code/admin/code_check_password_entry_needed4admin.php';

require ROOT.'include/page/admin/page_unban_form.php';

exit;

} while(false);//1

//##################################################################

do {//2

	if((!isset($_POST['unban_form']) and !isset($_POST['captcha'])) or isset($_POST['cancel'])) break;

	if(strtolower($_POST['username'])==='admin') {
		$err_msgs[]=func::tr('Admin account cannot be banned!');
		break;
	}
	
	require ROOT.'include/code/admin/code_check_password_entry_needed4admin.php';

	require ROOT.'include/code/admin/code_captcha8password_check.php';

	if(!isset($err_msgs)) {

		require ROOT.'include/code/admin/code_update_password_check.php';

		require ROOT.'include/code/admin/code_unban_user.php';
		
		exit;	

	}

	$user=$GLOBALS['reg8log_db']->quote_smart($_POST['username']);

	$query="select * from `accounts` where `username`=$user limit 1";

	if(!$GLOBALS['reg8log_db']->result_num($query)) {
		$err_msgs[]=func::tr('no such user found in the accounts table!');
		break;
	}

	$rec=$GLOBALS['reg8log_db']->fetch_row();

	if(strtolower($rec['username'])==='admin') {
		$err_msgs[]=func::tr('Admin account cannot be banned!');
		break;
	}

	$ban_until=$rec['banned'];

	$ban_reason ='';

	if($rec['banned']) {
		$username=$GLOBALS['reg8log_db']->quote_smart($rec['username']);
		$query="select * from `ban_info` where `username`=$username limit 1";
		if(!$GLOBALS['reg8log_db']->result_num($query)) echo func::tr('Info: No corresponding ban_info record found for the banned user!');
		else {
			$rec2=$GLOBALS['reg8log_db']->fetch_row();
			$ban_reason =$rec2['reason'];
		}
	}
	
	//-------------------

	require ROOT.'include/page/admin/page_unban_form.php';

	exit;

} while(false);//2

//##################################################################

require ROOT.'include/page/admin/page_ban_form1.php';

?>
