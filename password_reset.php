<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
$parent_page=true;

$index_dir='';

$store_request_entropy_probability2=1;

require $index_dir.'include/common.php';

require $index_dir.'include/code/code_encoding8anticache_headers.php';

if(!isset($_GET['rid'], $_GET['key'])) exit('<h3 align="center">Error: rid and/or key parameter is not set!</h3>');

if($_GET['rid']==='' or $_GET['key']==='') exit('<h3 align="center">Error: rid and/or key parameter is empty!</h3>');

require_once $index_dir.'include/code/code_db_object.php';

$rid=$reg8log_db->quote_smart($_GET['rid']);
$key=$reg8log_db->quote_smart($_GET['key']);

$query='select * from `password_reset` where `record_id`='.$rid." and `key`=$key limit 1";

if(!$reg8log_db->result_num($query)) exit('<center><h3>Error: No such record found!</h3><a href="index.php">Login page</a></center>');

$rec=$reg8log_db->fetch_row();
$_username=$rec['username'];

require $index_dir.'include/info/info_password_change_or_reset.php';

$expired=time()-$password_reset_period;

if($rec['timestamp']<$expired) exit('<center><h3>Error: Password reset link is expired!</h3><a href="index.php">Login page</a></center>');

require $index_dir.'include/info/info_register_fields.php';

$password_format=$fields['password'];

if(!isset($site_salt)) if(isset($_COOKIE['reg8log_site_salt'])) $site_salt=$_COOKIE['reg8log_site_salt'];
else {
	require $index_dir.'include/code/code_fetch_site_vars.php';
	setcookie('reg8log_site_salt', $site_salt, 0, '/', null, $https, true);
}

do {

if(!isset($_POST['newpass'], $_POST['repass'])) break;

require $index_dir.'include/code/code_prevent_repost.php';

require $index_dir.'include/code/code_prevent_xsrf.php';

require_once $index_dir.'include/func/func_utf8.php';


if(strpos($_POST['newpass'], "hashed-$site_salt")!==0) {
	if(utf8_strlen($_POST['newpass'])<$password_format['minlength'])
	$err_msgs[]="new password is shorter than {$password_format['minlength']} characters!";
	else if(utf8_strlen($_POST['newpass'])>$password_format['maxlength'])
	$err_msgs[]="new password is longer than {$password_format['maxlength']} characters!";
	else if($password_format['php_re'] and $_POST['newpass']!=='' and !preg_match($password_format['php_re'], $_POST['newpass']))
	$err_msgs[]="New password is invalid!";
	if($_POST['newpass']!==$_POST['repass'])
	$err_msgs[]="password fields aren't match!";
}

if(isset($err_msgs)) break;

require_once $index_dir.'include/func/func_secure_hash.php';

if(strpos($_POST['newpass'], "hashed-$site_salt")!==0) $_POST['newpass']='hashed-'.$site_salt.'-'.hash('sha256', $site_salt.$_POST['newpass']);
require $index_dir.'include/code/code_change_password.php';

$success_msg='<h3>Your password changed successfully.</h3>';
$no_specialchars=true;
require $index_dir.'include/page/page_success.php';

require $index_dir.'include/code/code_set_submitted_forms_cookie.php';

$query='delete from `password_reset` where `username`='.$reg8log_db->quote_smart($_username).' limit 1';
$reg8log_db->query($query);

exit;

} while(false);

require $index_dir.'include/page/page_password_reset_form.php';


?>