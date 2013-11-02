<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

?>

<html <?php echo $page_dir; ?>>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<META HTTP-EQUIV="EXPIRES" CONTENT="0">
<title><?php echo tr('Logging other systems out'); ?></title>
<script src="js/forms_common.js"></script>
<?php require $index_dir.'include/code/code_validate_captcha_field-js.php'; ?>
<script src="js/sha256.js"></script>
<script language="javascript">
function clear_form() {
	document.change_autologin_key_form.password.value='';
	clear_cap(true);
	return false;
}

<?php
echo "\nsite_salt='$site_salt';\n";
?>

function hash_password() {
	document.change_autologin_key_form.password.value='hashed-'+site_salt+'-'+hex_sha256(site_salt+document.change_autologin_key_form.password.value);
}

function validate() {//client side validator

msgs=new Array();

i=0;

if(!change_autologin_key_form.password.value.length) msgs[i++]="<?php echo tr('password field is empty!'); ?>";

if(captcha_exists) validate_captcha(document.change_autologin_key_form.captcha.value);

if(msgs.length) {
clear_cap(false);
for(i in msgs){
	msgs[i]=msgs[i].charAt(0).toUpperCase()+msgs[i].substring(1, msgs[i].length);
	cap.appendChild(document.createTextNode(msgs[i]));
	cap.appendChild(document.createElement("br"));
}
return false;
}

hash_password();

return true;
}//client side validator

</script>
</head>
<body bgcolor="#D1D1E9" text="#000000" link="#0000FF" vlink="#800080" alink="#FF0000" <?php echo $page_dir; ?>>
<table width="100%" height="100%"><tr><td align="center">
<form name="change_autologin_key_form" action="" method="post">
<table>
<tr><td align="center">
<table bgcolor="#dddddd" style="padding: 5px; border: 2px solid #000; padding: 7px">
<tr><td>
<?php

if(!empty($err_msgs)) {
	echo '<tr align="center"><td colspan="3"  style="border: solid thin orange; font-style: italic; background: #ccc; padding: 7px;"><span style="color: #800">', tr('Errors'), ':</span><br />';
	foreach($err_msgs as $err_msg) {
		$err_msg[0]=strtoupper($err_msg[0]);
		echo "<span style=\"color: red\" >$err_msg</span><br />";
	}
	echo '</td></tr><tr><td><div style="height: 10px">&nbsp;</div></td></tr>';
}

echo '<input type="hidden" name="antixsrf_token" value="';
echo $_COOKIE['reg8log_antixsrf_token4post'];
echo '">';

require $index_dir.'include/code/code_generate_form_id.php';

?>
<tr><td align="" style="" colspan="3" width="200">
<b><?php echo tr('change autologin key comments'); ?></b><br><br>
</td></tr>
<tr><td align="" style="" colspan="3"><b><?php echo tr('Enter your account password'); ?>:</b> <input type="password" name="password"></td></tr>
<?php
if(isset($captcha_needed) and !$captcha_verified) require $index_dir.'include/page/page_captcha_form.php';
?>
<tr>
<td align="center" colspan="3"><span style="color: red; font-style: italic" id="cap">&nbsp;</span></td>
</tr>
<tr>
<td align="center" colspan="3"><input type="reset" value="<?php echo tr('Clear'); ?>" onClick="return clear_form()" />
<input type="submit" value="<?php echo tr('Submit'); ?>" onClick="return validate()" /></td>
</tr></table></td></tr></table>
</form>
<center><a href="user_options.php"><?php echo tr('User options'); ?></a><br><br><a href="index.php"><?php echo tr('Login page'); ?></a></center>
</td></tr></table>
<script>
if(captcha_exists) {
	document.getElementById('re_captcha_msg').style.visibility='visible';
	captcha_img_style=document.getElementById('captcha_image').style;
	captcha_img_style.cursor='hand';
	if(captcha_img_style.cursor!='hand') captcha_img_style.cursor='pointer';
}
</script>
<?php
require $index_dir.'include/page/page_foot_codes.php';
?>
</body>
</html>
