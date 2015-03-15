<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

?>

<html <?php echo $page_dir; ?>>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<META HTTP-EQUIV="EXPIRES" CONTENT="0">
<title><?php echo func::tr('Send password reset email'); ?></title>
<script src="js/forms_common.js"></script>
<?php require ROOT.'include/code/code_validate_captcha_field-js.php'; ?>
<script>

var email_re=/^[a-z0-9_\-+\.]+@([a-z0-9\-+]+\.)+[a-z]{2,5}$/i;

function clear_form() {
document.reset_email_form.email.value='';
if(captcha_exists) document.getElementById('captcha_check_status').innerHTML='<?php echo func::tr('(Not case-sensitive)'); ?>';
clear_cap(true);
return false;
}

function validate()
{

clear_cap(true);

msgs=new Array();
i=0;
if(!document.reset_email_form.email.value) msgs[i++]='<?php echo func::tr('Email field is empty!'); ?>';
else if(!email_re.test(document.reset_email_form.email.value)) msgs[i++]='<?php echo func::tr('Email is invalid!'); ?>';
if(captcha_exists) validate_captcha(document.reset_email_form.captcha.value);
if(msgs.length) {
clear_cap(false);
for(i in msgs){
cap.appendChild(document.createTextNode(msgs[i]));
cap.appendChild(document.createElement("br"));
}
return false;
}

if(captcha_exists) {
	form_obj=document.reset_email_form;
	check_captcha();
	return false;
}

return true;
}

</script>
</head>
<body bgcolor="#D1D1E9" <?php echo $page_dir; ?>>
<table width="100%" >
<tr>
<td valign="top">
<?php
require ROOT.'include/page/page_sections.php';
?>
</td>
<tr>
<td align="center"><br>
<form name="reset_email_form" action="" method="post">
<table bgcolor="#7587b0">
<?php
if(isset($err_msgs)) {
echo '<tr align="center"><td colspan="3"  style="border: solid thin yellow; font-style: italic">';
foreach($err_msgs as $err_msg) echo '<span style="color: yellow" >', $err_msg, '</span><br />';
echo '</td></tr>';
}
?>
<tr><td><?php echo func::tr('Enter your account\'s email'); ?>:</td>
<td colspan="2"><input type="text" name="email" <?php if(isset($_POST['email'])) echo 'value="', htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8'), '"'; ?> size="30"></td></tr>
<?php

echo '<input type="hidden" name="antixsrf_token" value="';
echo $_SESSION['reg8log']['antixsrf_token4post'];
echo '">';

require ROOT.'include/code/code_generate_form_id.php';

if(isset($captcha_needed) and !$captcha_verified) require ROOT.'include/page/page_captcha_form.php';
?>
<tr><td align="center" colspan="3">
<span style="color: yellow; font-style: italic" id="cap">&nbsp;</span>
<div style="margin: 0px; padding: 0px; font-size: 1px">&nbsp;</div><input type="reset" value="<?php echo func::tr('Clear'); ?>" onClick="return clear_form()">
<input type="submit" value="<?php echo func::tr('Submit'); ?>" onclick="return validate()"></td></tr>
</table><br>
<?php echo func::tr('Check your email carefully msg'); ?>
<?php
if($max_password_reset_emails!=-1) {
require_once ROOT.'include/func/func_duration2friendly_str.php';
$period_msg=duration2friendly_str($password_reset_period, 0);
echo '<hr width="90%">', sprintf(func::tr('Max password reset emails msg'), $period_msg, $max_password_reset_emails);
}
?>
</td>
</tr>
</table>
</form>
<script>
if(captcha_exists) {
  document.getElementById('re_captcha_msg').style.visibility='visible';
  captcha_img_style=document.getElementById('captcha_image').style;
  captcha_img_style.cursor='hand';
  if(captcha_img_style.cursor!='hand') captcha_img_style.cursor='pointer';
}
</script>
<?php
require ROOT.'include/page/page_foot_codes.php';
?>
</body>
</html>
