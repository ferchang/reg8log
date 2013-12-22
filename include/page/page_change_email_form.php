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
<title><?php echo tr('Change email'); ?></title>
<style>
</style>
<script src="js/forms_common.js"></script>
<?php require $index_dir.'include/code/code_validate_captcha_field-js.php'; ?>
<script src="js/sha256.js"></script>
<script language="javascript">
function clear_form() {
document.change_email_form.password.value='';
document.change_email_form.newemail.value='';
document.change_email_form.reemail.value='';
clear_cap(true);
return false;
}

<?php
echo 'min_length=', $email_format['minlength'], ";\n";
echo 'max_length=', $email_format['maxlength'], ";\n";
echo 're=';
if($email_format['js_re']===true) echo $email_format['php_re'];
else if($email_format['js_re']===false) echo 'false';
else echo $email_format['js_re'];
echo ";\n";

echo "\nsite_salt='$site_salt';\n";
?>

function hash_password() {
document.change_email_form.password.value='hashed-'+site_salt+'-'+hex_sha256(site_salt+document.change_email_form.password.value);
}

function validate()
{
msgs=new Array();
i=0;

if(!document.change_email_form.password.value) msgs[i++]='<?php echo tr('Password field is empty!'); ?>';

newemail_value=document.change_email_form.newemail.value;
reemail_value=document.change_email_form.reemail.value;

if(newemail_value.length<min_length) msgs[i++]=<?php echo tr('New email is shorter than'); ?>
else if(newemail_value.length>max_length) msgs[i++]=<?php echo tr('New email is longer than'); ?>
else if(re && newemail_value && !re.test(newemail_value)) msgs[i++]="<?php echo tr('New email is invalid!'); ?>";
else if(newemail_value!=reemail_value) msgs[i++]='<?php echo tr('New email fields are not match!'); ?>';

if(captcha_exists) validate_captcha(document.change_email_form.captcha.value);

if(msgs.length) {
clear_cap(false);
for(i in msgs){
cap.appendChild(document.createTextNode(msgs[i]));
cap.appendChild(document.createElement("br"));
}
return false;
}

if(captcha_exists) {
	form_obj=document.change_email_form;
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
<form name="change_email_form" action="" method="post">
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
<td <?php echo $cell_align; ?>><?php echo tr('Your account Password'); ?>:</td><td><input type="password" name="password" size="30" style="width: 100%"  autocomplete="off" /></td>
</tr>
<tr>
<td <?php echo $cell_align; ?>><?php echo tr('New email'); ?>:</td><td><input type="text" name="newemail" size="30" style="width: 100%" <?php if(isset($_POST['newemail'])) echo 'value="', htmlspecialchars($_POST['newemail'], ENT_QUOTES, 'UTF-8'), '"'; ?>></td>
</tr>
<tr>
<td <?php echo $cell_align; ?>><?php echo tr('Retype new email'); ?>:</td><td><input type="text" name="reemail" size="30" style="width: 100%" <?php if(isset($_POST['reemail'])) echo 'value="', htmlspecialchars($_POST['reemail'], ENT_QUOTES, 'UTF-8'), '"'; ?>></td>
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
