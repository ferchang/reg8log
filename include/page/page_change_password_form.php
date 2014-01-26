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
<title><?php echo tr('Change password'); ?></title>
<style>
</style>
<script src="js/forms_common.js"></script>
<?php require $index_dir.'include/code/code_validate_captcha_field-js.php'; ?>
<script src="js/sha256.js"></script>
<script language="javascript">
function clear_form() {
	document.change_pass_form.curpass.value='';
	document.change_pass_form.newpass.value='';
	document.change_pass_form.repass.value='';
	if(captcha_exists) document.getElementById('captcha_check_status').innerHTML='<?php echo tr('(Not case-sensitive)'); ?>';
	clear_cap(true);
	return false;
}

<?php
echo 'min_length=', $password_format['minlength'], ";\n";
echo 'max_length=', $password_format['maxlength'], ";\n";
echo 're=';
if($password_format['js_re']===true) echo $password_format['php_re'];
else if($password_format['js_re']===false) echo 'false';
else echo $password_format['js_re'];
echo ";\n";

echo "\nsite_salt='$site_salt';\n";
?>

var auto_filled=false;
var password_value='';
var password_edited=false;

function hash_password() {
	document.change_pass_form.curpass.value='hashed-'+site_salt+'-'+hex_sha256(site_salt+document.change_pass_form.curpass.value);
	if(document.change_pass_form.newpass.value.indexOf('encrypted-'+site_salt)==0 || document.change_pass_form.newpass.value.indexOf('hashed-'+site_salt)==0) return;
	document.change_pass_form.newpass.value=document.change_pass_form.repass.value='hashed-'+site_salt+'-'+hex_sha256(site_salt+document.change_pass_form.newpass.value);
}

password_autofill_msg_flag=false;

function password_focus(p, i) {
	if(p.value.indexOf('encrypted-'+site_salt)==0 || p.value.indexOf('hashed-'+site_salt)==0) {
		if(!password_autofill_msg_flag) {
			alert('<?php echo tr('password_autofill_msg'); ?>');
			password_autofill_msg_flag=true;
			p.blur();
			return;
		}
		auto_filled=p;
		password_edited=false;
		password_value=p.value;
		p.value='';
	}
	else auto_filled=false;
}

function password_blur(p, i) {
	if(!auto_filled) return;
	if(!password_edited) p.value=password_value;
	else if(i==1 && document.change_pass_form.repass.value.indexOf('encrypted-'+site_salt)==0 || document.change_pass_form.repass.value.indexOf('hashed-'+site_salt)==0) document.change_pass_form.repass.value='';
}

function password_keydown(e) {
	if(!auto_filled) return;
	code = e.keyCode ? e.keyCode : e.which;
	if(code==27) {
		password_edited=false;
		auto_filled.blur();
		return false;
	}
	if(code==9) return;
	password_edited=true;
}

function validate()
{

clear_cap(true);

msgs=new Array();
i=0;

if(!document.change_pass_form.curpass.value) msgs[i++]='<?php echo tr('Current password field is empty!'); ?>';

newpass_value=document.change_pass_form.newpass.value;
repass_value=document.change_pass_form.repass.value;

if(newpass_value.indexOf('encrypted-'+site_salt)!=0 && newpass_value.indexOf('hashed-'+site_salt)!=0) {
	if(newpass_value.length<min_length) msgs[i++]="<?php echo tr('New password is shorter than "+min_length+" characters!'); ?>";
	else if(newpass_value.length>max_length) msgs[i++]="<?php echo tr('New password is longer than "+max_length+" characters!'); ?>";
	else if(re && newpass_value && !re.test(newpass_value)) msgs[i++]="<?php echo tr('New password is invalid!'); ?>";
}

if(newpass_value!=repass_value) msgs[i++]='<?php echo tr('New password fields are not match!'); ?>';

if(captcha_exists) validate_captcha(document.change_pass_form.captcha.value);

if(msgs.length) {
	clear_cap(false);
	for(i in msgs){
		cap.appendChild(document.createTextNode(msgs[i]));
		cap.appendChild(document.createElement("br"));
	}
	return false;
}

if(captcha_exists) {
	form_obj=document.change_pass_form;
	check_captcha();
	return false;
}

hash_password();

return true;
}

</script>
</head>
<body bgcolor="#D1D1E9" text="#000000" link="#0000FF" vlink="#800080" alink="#FF0000" style="margin: 0" <?php echo $page_dir; ?>>
<table width="100%"  height="100%" cellpadding="5" cellspacing="0">
<tr>
<td>
<table bgcolor="#7587b0" align="center">
<form name="change_pass_form" action="" method="post">
<?php

echo '<input type="hidden" name="antixsrf_token" value="';
echo $_COOKIE['reg8log_antixsrf_token4post'];
echo '">';

require $index_dir.'include/code/code_generate_form_id.php';

if(isset($err_msgs)) {
echo '<tr align="center"><td colspan="3"  style="border: solid thin yellow; font-style: italic"><span style="color: #800">', tr('Errors'), ':</span><br />';
foreach($err_msgs as $err_msg) {
$err_msg[0]=strtoupper($err_msg[0]);
echo "<span style=\"color: yellow\" >$err_msg</span><br />";
}
echo '</td></tr>';
}
?>
<tr>
<td <?php echo $cell_align; ?>><?php echo tr('Your current Password'); ?>:</td><td><input type="password" name="curpass" size="30" style="width: 100%"  autocomplete="off" /></td>
</tr>
<tr>
<td <?php echo $cell_align; ?>><?php echo tr('New password'); ?>:</td><td><input type="password"  autocomplete="off" name="newpass" size="30" style="" onfocus="password_focus(this, 1);" onblur="password_blur(this, 1);" onkeydown="password_keydown(event);" 
<?php
if(isset($_POST['newpass']) and $_POST['newpass']!=='' and $password_refill and !isset($password_error)) {
	$refill=$_POST['newpass'];
	require $index_dir.'include/code/code_refill_password.php';
}
?>/></td>
</tr>
<tr>
<td <?php echo $cell_align; ?>><?php echo tr('Retype new Password'); ?>:</td><td><input type="password"  autocomplete="off" name="repass" size="30" style="" onfocus="password_focus(this, 2);" onblur="password_blur(this, 2);" onkeydown="password_keydown(event);" 
<?php
if(isset($refill_output)) echo $refill_output;
?>/>
</td>
</tr>
<?php
if(isset($captcha_needed) and !$captcha_verified) require $index_dir.'include/page/page_captcha_form.php';
?>
<tr>
<td></td><td><span style="color: yellow; font-style: italic" id="cap">&nbsp;</span></td>
</tr>
<tr>
<td></td>
<td align="center"><input type="reset" value="<?php echo tr('Clear'); ?>" onClick="return clear_form()" />
<input type="submit" value="<?php echo tr('Submit'); ?>" onClick="return validate()" /></td>
</tr>
</table>
</form>
<br><center><a href="user_options.php"><?php echo tr('User options'); ?></a><br><br><a href="index.php"><?php echo tr('Login page'); ?></a></center>
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
