<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

require_once $index_dir.'include/config/config_admin.php';

if(!$admin_operations_require_password) return;

$password_check_needed=true;

do {

	if($admin_operations_require_password==1) break;

	if(!isset($_COOKIE['reg8log_password_check_key'])) break;

	$query='select * from `admin` limit 1';

	$reg8log_db->query($query);

	$tmp42=$reg8log_db->fetch_row();

	if($req_time>$tmp42['last_password_check']+$admin_operations_require_password) {
		setcookie('reg8log_password_check_key', false, mktime(12,0,0,1, 1, 1990), '/', null, $https, true);
		break;
	}

	if($tmp42['password_check_key']!=$_COOKIE['reg8log_password_check_key']) {
		setcookie('reg8log_password_check_key', false, mktime(12,0,0,1, 1, 1990), '/', null, $https, true);
		break;
	}

	unset($password_check_needed);

} while(false);

if(isset($password_check_needed)) {
	$try_type='password';
	require $index_dir.'include/code/code_check_captcha_needed4user.php';

	if(isset($captcha_needed)) {
		require $index_dir.'include/code/sess/code_sess_start.php';
		$captcha_verified=isset($_SESSION['captcha_verified']);
	}
}

?>