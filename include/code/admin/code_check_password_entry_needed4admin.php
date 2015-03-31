<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(!config::get('admin_operations_require_password')) return;

$password_check_needed=true;

do {

	if(config::get('admin_operations_require_password')===1) break;

	if(!isset($_COOKIE['reg8log_password_check_key'])) break;

	$query='select * from `admin` limit 1';

	$reg8log_db->query($query);

	$tmp42=$reg8log_db->fetch_row();

	if($req_time>$tmp42['last_password_check']+config::get('admin_operations_require_password')) {
		setcookie('reg8log_password_check_key', false, mktime(12,0,0,1, 1, 1990), '/', null, HTTPS, true);
		break;
	}

	if($tmp42['password_check_key']!=$_COOKIE['reg8log_password_check_key']) {
		setcookie('reg8log_password_check_key', false, mktime(12,0,0,1, 1, 1990), '/', null, HTTPS, true);
		break;
	}

	unset($password_check_needed);

} while(false);

if(isset($password_check_needed)) {
	$try_type='password';
	require ROOT.'include/code/code_check_captcha_needed4user.php';

	if(isset($captcha_needed)) {
		require ROOT.'include/code/sess/code_sess_start.php';
		$captcha_verified=isset($_SESSION['reg8log']['captcha_verified']);
	}
}

?>