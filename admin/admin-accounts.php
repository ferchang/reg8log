<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
$parent_page=true;

$index_dir='../';

$store_request_entropy_probability2=1;

require_once $index_dir.'include/common.php';

require $index_dir.'include/code/code_encoding8anticache_headers.php';

if(!isset($site_salt)) if(isset($_COOKIE['reg8log_site_salt'])) $site_salt=$_COOKIE['reg8log_site_salt'];
else {
	require $index_dir.'include/code/code_fetch_site_vars.php';
	setcookie('reg8log_site_salt', $site_salt, 0, '/', null, $https, true);
}

require $index_dir.'include/code/admin/code_require_admin.php';

$sort_fields=array('uid', 'username', 'email', 'gender', 'banned', 'timestamp', 'last_activity', 'last_logout', 'last_login');
require $index_dir.'include/code/admin/code_pagination_params.php';

require_once $index_dir.'include/code/code_db_object.php';

//-------------

require $index_dir.'include/config/config_admin.php';

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

if(isset($_POST['delete']) or isset($_POST['captcha'])) {

	require $index_dir.'include/code/code_prevent_xsrf.php';

	if(isset($captcha_needed) and !$captcha_verified) require $index_dir.'include/code/code_verify_captcha.php';
	
	if(isset($password_check_needed)) {//password_check_needed
		if(!isset($_POST['password'])) $err_msgs[]=$password_msg=tr('Sorry, but entering Admin password is needed!');
		else {
			$password=$_POST['password'];
			if($password=='') $err_msgs[]=tr('Password field is empty!');
			else if(!isset($captcha_err)) {
				unset($captcha_verified);
				if(isset($_SESSION['captcha_verified'])) unset($_SESSION['captcha_verified']);
				if(strpos($_POST['password'], "hashed-$site_salt")!==0) $_POST['password']='hashed-'.$site_salt.'-'.hash('sha256', $site_salt.$_POST['password']);
				require $index_dir.'include/code/code_verify_password.php';
				if(isset($err_msgs)) {
					$try_type='password';
					require $index_dir.'include/code/code_update_user_last_ch_try.php';
				}
				else if(isset($_COOKIE['reg8log_ch_pswd_try'])) {
					if(is_numeric($_COOKIE['reg8log_ch_pswd_try'])) {
						$query='update `accounts` set `ch_pswd_tries`=`ch_pswd_tries`-'.$_COOKIE['reg8log_ch_pswd_try'].' where `username`='.$reg8log_db->quote_smart($identified_user)." and `ch_pswd_tries`>={$_COOKIE['reg8log_ch_pswd_try']} limit 1";
						$reg8log_db->query($query);
					}
					setcookie('reg8log_ch_pswd_try', false, mktime(12,0,0,1, 1, 1990), '/', null, $https, true);
				}
			}
		}
	}//password_check_needed
	
	if(!isset($err_msgs)) {
		if($admin_operations_require_password>1) {
			require_once $index_dir.'include/func/func_random.php';
			$password_check_key=random_string(22);
			$query='update `admin` set ';
			if(isset($password_check_needed)) $query.="`last_password_check`=$req_time, ";
			$query.="`password_check_key`='$password_check_key' limit 1";
			$reg8log_db->query($query);
			setcookie('reg8log_password_check_key', $password_check_key, 0, '/', null, $https, true);
			unset($password_check_needed, $captcha_needed);
		}
		foreach($_POST as $auto=>$action) if($action=='del') $del[]=$auto;
		if(isset($del)) require $index_dir.'include/code/admin/code_delete_accounts.php';
		unset($captcha_verified);
		if(isset($_SESSION['captcha_verified'])) unset($_SESSION['captcha_verified']);
	}
	//else echo $err_msgs;

}

$query="select * from `accounts` where `username`!='Admin'";

if(!$total=$reg8log_db->result_num($query)) {
	if(isset($queries_executed)) echo '<center style="color: #fff; background: green; padding: 3px; font-weight: bold; margin-bottom: 5px">', tr('Your command(s) were executed.', true), '</center>';
	my_exit('<center><h3>'.tr('No accounts found.').'</h3><a href="index.php">'.tr('Admin operations').'</a><br><br><a href="../index.php">'.tr('Login page').'</a></center>');
}

require $index_dir.'include/code/admin/code_pagination_params2.php';

$query="select * from `accounts` where `username`!='Admin' order by `$sort_by` $sort_dir, `auto` limit "."$per_page offset $offset";

$reg8log_db->query($query);

require $index_dir.'include/page/admin/page_accounts.php';

?>