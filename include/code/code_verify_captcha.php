<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");



require_once $index_dir.'include/func/func_captcha.php';

require_once $index_dir.'include/info/info_register_fields.php';

$captcha_format=$fields['captcha'];

require $index_dir.'include/code/code_sess_start.php';

$captcha_verified=false;

if(!isset($_SESSION['captcha_hash'], $_POST['captcha'])) {
	$err_msgs[]=$captcha_msg='Sorry, you need to enter a security code.';
	$captcha_err=true;
}
else if($_POST['captcha']==='') {
	$err_msgs[]='Security code field is empty!';
	$captcha_err=true;
}
else if(strlen($_POST['captcha'])<$captcha_format['minlength']) {
	$err_msgs[]="Security code is shorter than {$captcha_format['minlength']} characters!";
	$captcha_err=true;
}
else if(strlen($_POST['captcha'])>$captcha_format['maxlength']) {
	$err_msgs[]="Security code is longer than {$captcha_format['maxlength']} characters!";
	$captcha_err=true;
}
else if($captcha_format['php_re'] and !preg_match($captcha_format['php_re'], $_POST['captcha'])) {
	$err_msgs[]="Security code contains invalid characters!";
	$captcha_err=true;
}
else if(!captcha_verify_word()) {
	$err_msgs[]='The security code was incorrect!';
	$captcha_err=true;
}
else $captcha_verified=true;

?>