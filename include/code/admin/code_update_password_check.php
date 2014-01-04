<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

$captcha_verified=false;
if(isset($_SESSION['captcha_verified'])) unset($_SESSION['captcha_verified']);

if($admin_operations_require_password>1) {
	if(!isset($password_check_needed) or isset($_POST['remember'])) {
		require_once $index_dir.'include/func/func_random.php';
		$password_check_key=random_string(22);
		$query='update `admin` set ';
		if(isset($password_check_needed)) $query.="`last_password_check`=$req_time, ";
		$query.="`password_check_key`='$password_check_key' limit 1";
		$reg8log_db->query($query);
		setcookie('reg8log_password_check_key', $password_check_key, 0, '/', null, $https, true);
		unset($password_check_needed, $captcha_needed);
	}
}

?>