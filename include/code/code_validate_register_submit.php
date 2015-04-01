<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$_POST['username']=func::fix_kaaf8yeh($_POST['username']);

foreach($_fields as $field_name=>$specs) {//validate post data

$ct=count($err_msgs);

if($field_name==='password' and (strpos($_POST[$field_name], "hashed-$site_salt")===0 or strpos($_POST[$field_name], "encrypted-$site_salt")===0)) {
	if($_POST['password']!==$_POST['repass']) {
		$err_msgs[]=func::tr('password fields aren\'t match!');
		$password_error=true;
	}
	else if(strpos($_POST[$field_name], "encrypted-$site_salt")===0) {
		
		if(!func::verify_hmac(base64_decode(substr($_POST['password'], strrpos($_POST['password'], '-')+1)))) {
			$err_msgs[]=func::tr('error in password decryption!');
			$password_error=true;
		}
	}
	continue;
}

if($field_name==='captcha') {
	if($captcha_verified) continue;
	require ROOT.'include/code/code_verify_captcha.php';
	continue;
}

$min_length=$specs['minlength'];
$max_length=$specs['maxlength'];
$re=$specs['php_re'];
$unique=$specs['unique'];

if(!isset($_POST[$field_name])) $err_msgs[]=sprintf(func::tr('No %s field exist!'), $field_name);
else {//field exists

$field_value=$_POST[$field_name];

$_fields[$field_name]['value']=$field_value;

if(func::utf8_strlen($field_value)<$min_length)
$err_msgs[]=func::tr($field_name).sprintf(func::tr(' is shorter than %d characters!'), $min_length);
else if(func::utf8_strlen($field_value)>$max_length)
$err_msgs[]=func::tr($field_name).sprintf(func::tr(' is longer than %d characters!'), $max_length);
else if($re and $field_value!=='' and !preg_match($re, $field_value))
$err_msgs[]=func::tr($field_name).func::tr(' is invalid!');
else if($unique and !isset($captcha_err)) {
	require ROOT.'include/code/code_check_field_uniqueness.php';
	if(isset($uniqueness_err) and ($field_name!=='username')) {
		unset($_SESSION['reg8log']['captcha_verified']);
		$captcha_verified=false;
	}
}

if(count($err_msgs)===$ct) {
	if($field_name==='password' and $field_value!==$_POST['repass']) $err_msgs[]=func::tr('password fields aren\'t match!');
	else if($field_name==='email' and $field_value!==$_POST['reemail']) $err_msgs[]=func::tr('email fields aren\'t match!');
	else if($field_name==='username' and strtolower($field_value)==='admin') $err_msgs[]=func::tr('username \'Admin\' is reserved!');
}

/*
we store accepted unique field values in the session and
if their value changed in the next submit,
we invalidate a successful captcha test to
force the client to do a captcha test again.
this is to prevent misusing the system with bots to get knowledge about registered
unique field values in our members database.
*/
if(count($err_msgs)===$ct and $unique and !isset($captcha_err) and ($field_name!=='username' or !config::get('ajax_check_username') or config::get('max_ajax_check_usernames'))) {
		if(isset($_SESSION['reg8log']['passed'][$field_name]) and $_SESSION['reg8log']['passed'][$field_name]!==sha1($session_salt.$field_value, true)) {
				unset($_SESSION['reg8log']['captcha_verified']);
				$err_msgs[]=func::tr('You need to enter a security code again.');
				$captcha_verified=false;
			}
		$_SESSION['reg8log']['passed'][$field_name]=sha1($session_salt.$field_value, true);
}

}//field exists

if(count($err_msgs)!==$ct and $field_name==='password') $password_error=true;

}//validate post data

?>
