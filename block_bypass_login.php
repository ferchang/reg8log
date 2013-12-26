<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
$parent_page=true;

$index_dir='./';

$store_request_entropy_probability2=1;

require $index_dir.'include/common.php';

require $index_dir.'include/code/code_encoding8anticache_headers.php';

$block_bypass_mode=true;

require $index_dir.'include/config/config_brute_force_protection.php';

if(!$block_bypass_system_enabled) exit('<h3 align="center">Block-bypass system is disabled by administrator!</h3>');

if(!isset($_GET['key'])) exit('<h3 align="center">Error: key parameter is not set!</h3>');

if(isset($_POST['login2ip'])) $login2ip=true;
else $login2ip=false;

if(!isset($site_salt)) if(isset($_COOKIE['reg8log_site_salt'])) $site_salt=$_COOKIE['reg8log_site_salt'];
else {
	require $index_dir.'include/code/code_fetch_site_vars.php';
	setcookie('reg8log_site_salt', $site_salt, 0, '/', null, $https, true);
}

if(isset($_POST['username']) and isset($_POST['password'])) {//1

require $index_dir.'include/code/code_prevent_repost.php';

require_once $index_dir.'include/func/func_yeh8kaaf.php';
fix_yeh8kaaf($_POST['username']);
	
if(strpos($_POST['password'], "hashed-$site_salt")!==0) $_POST['password']='hashed-'.$site_salt.'-'.hash('sha256', $site_salt.$_POST['password']);

$manual_login=array('username'=>$_POST['username'], 'password'=>$_POST['password']);

if($block_bypass_system_enabled==1 and strtolower($_POST['username'])!='admin') exit('<h3 align="center">Currently, block-bypass system is enabled only for the admin account!</h3>');

if($block_bypass_system_enabled==2 and strtolower($_POST['username'])=='admin') exit('<h3 align="center">Currently, block-bypass system is disabled for the admin account!</h3>');

$_username=$_POST['username'];
require $index_dir.'include/code/code_check_account_block.php';

if(!isset($account_block)) {

	if(!$block_bypass_system_also4ip_block) my_exit('<h3 align=center>'.sprintf(tr('account is not blocked msg'), htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8')).'</h3><center><a href="index.php">'.tr('Login page').'</a></center>');
	
	$_username=$_POST['username'];
	require $index_dir.'include/code/code_check_ip_block.php';
	
	if(!isset($ip_block)) my_exit('<h3 align=center>'.sprintf(tr('account or ip is not blocked msg'), htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8'), $_SERVER['REMOTE_ADDR']).'</h3><center><a href="index.php">'.tr('Login page').'</a></center>');

}

require_once $index_dir.'include/code/code_db_object.php';

$_username=$reg8log_db->quote_smart($_POST['username']);
$query="select * from `block_bypass` where `username`=$_username limit 1";

if(!$reg8log_db->result_num($query)) my_exit('<h3 align="center">'.tr('Error: Block-bypass link not verified!').'</h3>');

$rec=$reg8log_db->fetch_row();
$key=$rec['key'];
$incorrect_logins=$rec['incorrect_logins'];
$block_bypass_record_auto=$rec['auto'];

if($_GET['key']!==$key) my_exit('<h3 align="center">'.tr('Error: Block-bypass link not verified!').'</h3>');

if($block_bypass_max_incorrect_logins and $incorrect_logins>=$block_bypass_max_incorrect_logins) my_exit('<center><h3>'.tr('max incorrect logins reached msg').'</h3><br><a href="index.php">'.tr('Login page').'</a></center>');

unset($identified_user);
unset($identify_error);

$pass_banned_user=true;
require $index_dir.'include/code/code_identify.php';

if(isset($identify_error)) {
	$failure_msg=($debug_mode)? $user->err_msg : tr('Identification error');
	require $index_dir.'include/page/page_failure.php';
	exit;
}

if(isset($identified_user)) {//2

	$_identified_username=$identified_user;

	require $index_dir.'include/code/dec/code_dec_incorrect_logins.php';

	//=================
	require_once $index_dir.'include/func/func_autologin_ages.php';
	$autologin_ages=get_autologin_ages();

	if(isset($_POST['autologin_age'])) {
		if(!in_array($_POST['autologin_age'], $autologin_ages)) {
			$autologin_age_msg=tr('illegal autologin_age msg');
			require $index_dir.'include/page/page_login_form.php';
			exit;
		}
		$autologin_age=$_POST['autologin_age'];
	}
	else {
		if(count($autologin_ages)!=1) {
			$autologin_age_msg=tr('illegal autologin_age msg');
			require $index_dir.'include/page/page_login_form.php';
			exit;
		}
		$autologin_age=$autologin_ages[0];
	}

	$user->save_identity($autologin_age, false, true);
	//=================

	header('Location: index.php');

	exit;

}//2
else if(isset($pending_user)) {
	$_identified_username=$pending_user;

	require $index_dir.'include/code/dec/code_dec_incorrect_logins.php';

	require $index_dir.'include/code/code_detect8fix_failed_activation.php';
	require $index_dir.'include/page/page_pending_user.php';
	exit;
}
//--------------------
else if(isset($banned_user)) {
	if(isset($manual_login)) {

		//=================
		require_once $index_dir.'include/func/func_autologin_ages.php';
		$autologin_ages=get_autologin_ages();

		if(isset($_POST['autologin_age'])) {
			if(!in_array($_POST['autologin_age'], $autologin_ages)) {
				$autologin_age_msg=tr('illegal autologin_age msg');
				require $index_dir.'include/page/page_login_form.php';
				exit;
			}
			$autologin_age=$_POST['autologin_age'];
		}
		else {
			if(count($autologin_ages)!=1) {
				$autologin_age_msg=tr('illegal autologin_age msg');
				require $index_dir.'include/page/page_login_form.php';
				exit;
			}
			$autologin_age=$autologin_ages[0];
		}

		$user->save_identity($autologin_age, false, true);
		//=================

	}
	require $index_dir.'include/page/page_banned_user.php';
	exit;
}
//--------------------
else {
	require $index_dir.'include/code/code_set_submitted_forms_cookie.php';
	require $index_dir.'include/code/code_block_bypass_incorrect_login.php';
	$err_msg=tr('Check login information msg');
}

}//1

require $index_dir.'include/page/page_login_form.php';

?>