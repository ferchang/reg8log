<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

?>

<html <?php echo PAGE_DIR; ?>>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<META HTTP-EQUIV="EXPIRES" CONTENT="0">
<title><?php echo func::tr('Block-bypass link request'); ?></title>
<script src="js/forms_common.js"></script>
<?php require ROOT.'include/code/code_validate_captcha_field-js.php'; ?>
<script>

var email_re=/^[a-z0-9_\-+\.]+@([a-z0-9\-+]+\.)+[a-z]{2,5}$/i;

function clear_form() {
document.bypass_form.email.value='';
if(captcha_exists) document.getElementById('captcha_check_status').innerHTML='<?php echo func::tr('(Not case-sensitive)'); ?>';
clear_cap(true);
return false;
}

function validate()
{

clear_cap(true);

msgs=new Array();
i=0;
if(!document.bypass_form.email.value) msgs[i++]='<?php echo func::tr('Email field is empty!'); ?>';
else if(!email_re.test(document.bypass_form.email.value)) msgs[i++]='<?php echo func::tr('Email is invalid!'); ?>';
if(captcha_exists) validate_captcha(document.bypass_form.captcha.value);
if(msgs.length) {
clear_cap(false);
for(i in msgs){
cap.appendChild(document.createTextNode(msgs[i]));
cap.appendChild(document.createElement("br"));
}
return false;
}

if(captcha_exists) {
	form_obj=document.bypass_form;
	check_captcha();
	return false;
}

return true;
}

</script>
</head>
<body bgcolor="#7587b0" <?php echo PAGE_DIR; ?>>
<table width="100%" >
<tr>
<td valign="top">
<?php
require ROOT.'include/page/page_sections.php';
?>
</td>
<tr>
<td align="center"><br>
<form name="bypass_form" action="" method="post">
<table style="margin: 5px">
<?php

echo '<input type="hidden" name="antixsrf_token" value="';
echo ANTIXSRF_TOKEN4POST;
echo '">';

if(isset($err_msgs)) {
echo '<tr align="center"><td colspan="3"  style="border: solid thin yellow; font-style: italic">';
foreach($err_msgs as $err_msg) echo '<span style="color: yellow" >', $err_msg, '</span><br />';
echo '</td></tr>';
}
?>
<tr><td><?php echo func::tr('Enter your account\'s email'); ?>:</td>
<td colspan="2"><input type="text" name="email" <?php if(isset($_POST['email'])) echo 'value="', htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8'), '"'; ?> size="30"></td></tr>
<?php
require ROOT.'include/code/code_generate_form_id.php';

if(isset($captcha_needed) and !$captcha_verified) require ROOT.'include/page/page_captcha_form.php';
?>
<tr><td align="center" colspan="3">
<br><input type="reset" value="<?php echo func::tr('Clear'); ?>" onClick="return clear_form()">
<input type="submit" value="<?php echo func::tr('Submit'); ?>" onclick="return validate()"></td></tr>
</table>
<span style="color: yellow; font-style: italic" id="cap">&nbsp;</span>
<?php echo func::tr('Check your email carefully msg2'); ?>
<?php
if(config::get('max_block_bypass_emails')!==-1) {
echo '<hr width="90%">', func::tr('Maximum number of block-bypass emails that can be sent'), ': ', config::get('max_block_bypass_emails'), '<br>';
echo func::tr('Note that the system will not, for security reasons, tell you if the maximum number of emails is reached.');
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
