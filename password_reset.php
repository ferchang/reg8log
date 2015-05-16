<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
define('CAN_INCLUDE', true);

$store_request_entropy_probability2=1;

require 'include/common.php';

if(!isset($_GET['rid'], $_GET['key'])) exit('<h3 align="center">Error: rid and/or key parameter is not set!</h3>');

if($_GET['rid']==='' or $_GET['key']==='') exit('<h3 align="center">Error: rid and/or key parameter is empty!</h3>');

$rid=$GLOBALS['reg8log_db']->quote_smart($_GET['rid']);
$key=$GLOBALS['reg8log_db']->quote_smart($_GET['key']);

$query='select * from `password_reset` where `record_id`='.$rid." and `key`=$key limit 1";

if(!$GLOBALS['reg8log_db']->result_num($query)) func::my_exit('<center><h3>'.func::tr('Error: No such record found').'!</h3><a href="index.php">'.func::tr('Login page').'</a></center>');

$rec=$GLOBALS['reg8log_db']->fetch_row();
$_username=$rec['username'];

$expired=REQUEST_TIME-config::get('password_reset_period');

if($rec['timestamp']<$expired) func::my_exit('<center><h3>'.func::tr('Error: Password reset link is expired').'!</h3><a href="index.php">'.func::tr('Login page').'</a></center>');

$_fields=config::get('fields');

$password_format=$_fields['password'];

do {

if(!isset($_POST['newpass'], $_POST['repass'])) break;

require ROOT.'include/code/code_prevent_repost.php';

require ROOT.'include/code/code_prevent_xsrf.php';

if(strpos($_POST['newpass'], 'hashed-'.SITE_SALT)!==0) {
	if(func::utf8_strlen($_POST['newpass'])<$password_format['minlength'])
	$err_msgs[]=sprintf(func::tr('new password is shorter than'), $password_format['minlength']);
	else if(func::utf8_strlen($_POST['newpass'])>$password_format['maxlength'])
	$err_msgs[]=sprintf(func::tr('new password is longer than'), $password_format['maxlength']);
	else if($password_format['php_re'] and $_POST['newpass']!=='' and !preg_match($password_format['php_re'], $_POST['newpass']))
	$err_msgs[]=func::tr('New password is invalid!');
	if($_POST['newpass']!==$_POST['repass'])
	$err_msgs[]=func::tr('password fields aren\'t match!');
}

if(isset($err_msgs)) break;

if(strpos($_POST['newpass'], 'hashed-'.SITE_SALT)!==0) $_POST['newpass']='hashed-'.SITE_SALT.'-'.hash('sha256', SITE_SALT.$_POST['newpass']);
require ROOT.'include/code/code_change_password.php';

require ROOT.'include/code/code_set_submitted_forms_cookie.php';

$success_msg='<h3>'.func::tr('Your password changed successfully').'.</h3>';
$no_specialchars=true;
require ROOT.'include/page/page_success.php';

$query='delete from `password_reset` where `username`='.$GLOBALS['reg8log_db']->quote_smart($_username).' limit 1';
$GLOBALS['reg8log_db']->query($query);

exit;

} while(false);

require ROOT.'include/page/page_password_reset_form.php';

?>