<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");



$_POST['username']=str_replace(array('ي', 'ك'), array('ی', 'ک'), $_POST['username']);

foreach($fields as $field_name=>$specs) {//validate post data

$ct=count($err_msgs);

if($field_name=='password' and (strpos($_POST[$field_name], "hashed-$site_salt")===0 or strpos($_POST[$field_name], "encrypted-$site_salt")===0)) {
	if($_POST['password']!==$_POST['repass']) {
		$err_msgs[]="password fields aren't match!";
		$password_error=true;
	}
	else if(strpos($_POST[$field_name], "encrypted-$site_salt")===0) {
		require_once $index_dir.'include/func/func_site8client_keys_hmac_verifier.php';
		if(!verify_hmac(base64_decode(substr($_POST['password'], strrpos($_POST['password'], '-')+1)))) {
			$err_msgs[]="error in password decryption!";
			$password_error=true;
		}
	}
	continue;
}

if($field_name=='captcha') {
	if($captcha_verified) continue;
	require $index_dir.'include/code/code_verify_captcha.php';
	continue;
}

$min_length=$specs['minlength'];
$max_length=$specs['maxlength'];
$re=$specs['php_re'];
$unique=$specs['unique'];

if(!isset($_POST[$field_name])) $err_msgs[]="No $field_name field exist!";
else {//field exists

$field_value=$_POST[$field_name];

$fields[$field_name]['value']=$field_value;

if(utf8_strlen($field_value)<$min_length)
$err_msgs[]="$field_name is shorter than $min_length characters!";
else if(utf8_strlen($field_value)>$max_length)
$err_msgs[]="$field_name is longer than $max_length characters!";
else if($re and $field_value!=='' and !preg_match($re, $field_value))
$err_msgs[]="$field_name is invalid!";
else if($unique and !isset($captcha_err)) {
	require $index_dir.'include/code/code_check_field_uniqueness.php';
	if(isset($uniqueness_err) and ($field_name!='username')) {
		unset($_SESSION['captcha_verified']);
		$captcha_verified=false;
	}
}

if(count($err_msgs)===$ct) {
	if($field_name=='password' and $field_value!=$_POST['repass']) $err_msgs[]="password fields aren't match!";
	else if($field_name=='email' and $field_value!=$_POST['reemail']) $err_msgs[]="email fields aren't match!";
	else if($field_name=='username' and strtolower($field_value)==='admin') $err_msgs[]="username 'Admin' is reserved!";
}

/*
we store accepted unique field values in the session and
if their value changed in the next submit,
we invalidate a successful captcha test to
force the client to do a captcha test again.
this is to prevent misusing the system with bots to get knowledge about registered
unique field values in our members database.
*/
if(count($err_msgs)==$ct and $unique and !isset($captcha_err) and ($field_name!='username' or !$ajax_check_username)) {
		if(isset($_SESSION['passed'][$field_name]) and $_SESSION['passed'][$field_name]!=sha1($session_salt.$field_value, true)) {
				unset($_SESSION['captcha_verified']);
				$err_msgs[]='You need to enter a security code again.';
				$captcha_verified=false;
			}
		$_SESSION['passed'][$field_name]=sha1($session_salt.$field_value, true);
}

}//field exists

if(count($err_msgs)!==$ct and $field_name=='password') $password_error=true;

}//validate post data

?>
