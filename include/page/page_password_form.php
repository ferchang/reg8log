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
<title><?php echo func::tr('Account info'); ?></title>
<style>
</style>
<script src="js/forms_common.js"></script>
<?php require ROOT.'include/code/code_validate_captcha_field-js.php'; ?>
<script src="js/sha256.js"></script>
<script language="javascript">
function clear_form() {
	document.pass_form.password.value='';
	if(captcha_exists) document.getElementById('captcha_check_status').innerHTML='<?php echo func::tr('(Not case-sensitive)'); ?>';
	clear_cap(true);
	return false;
}

<?php
echo "\nsite_salt='".SITE_SALT."';\n";
?>

function hash_password() {
	document.pass_form.password.value='hashed-'+site_salt+'-'+hex_sha256(site_salt+document.pass_form.password.value);
}

function validate() {

	clear_cap(true);

	msgs=new Array();
	i=0;

	if(!document.pass_form.password.value) msgs[i++]='<?php echo func::tr('Password field is empty!'); ?>';

	if(captcha_exists) validate_captcha(document.pass_form.captcha.value);
	
	if(msgs.length) {
		clear_cap(false);
		for(i in msgs) {
			cap.appendChild(document.createTextNode(msgs[i]));
			cap.appendChild(document.createElement("br"));
		}
		return false;
	}
	
	if(captcha_exists) {
		form_obj=document.pass_form;
		check_captcha();
		return false;
	}

	hash_password();

	return true;
}

</script>
</head>
<body bgcolor="#D1D1E9" text="#000000" link="#0000FF" vlink="#800080" alink="#FF0000" style="margin: 0" <?php echo PAGE_DIR; ?>>
<table width="100%"  height="100%" cellpadding="5" cellspacing="0">
<tr>
<td>
<form name="pass_form" action="" method="post">
<table bgcolor="#7587b0" align="center" style='padding: 5px'>
<?php

echo '<input type="hidden" name="antixsrf_token" value="';
echo ANTIXSRF_TOKEN4POST;
echo '">';

require ROOT.'include/code/code_generate_form_id.php';

if(isset($err_msgs)) {
	echo '<tr align="center"><td colspan="3"  style="border: solid thin yellow; font-style: italic"><span style="color: #800">', func::tr('Errors'), ':</span><br />';
	foreach($err_msgs as $err_msg) {
		$err_msg[0]=strtoupper($err_msg[0]);
		echo "<span style=\"color: yellow\" >$err_msg</span><br />";
	}
	echo '</td></tr>';
}
?>
<tr>
<td  <?php echo CELL_ALIGN; ?> ><?php echo func::tr('Password'); ?>:</td><td><input type="password" name="password" size="30" style="width: 100%"  autocomplete="off" />
<input type="text" name="dummy" style="display: none" disabled />
<!-- i added this dummy input because, otherwise, IE8 doesn't execute submit button's onclick when the form is submitted with the Enter key -->
</td>
</tr>
<?php
if(isset($captcha_needed) and !$captcha_verified) require ROOT.'include/page/page_captcha_form.php';
?>
<tr>
<td></td><td><span style="color: yellow; font-style: italic" id="cap">&nbsp;</span></td>
</tr>
<tr>
<td></td>
<td align="center"><input type="reset" value="<?php echo func::tr('Clear'); ?>" onClick="return clear_form()" />
<input type="submit" value="<?php echo func::tr('Submit'); ?>" onClick="return validate()" ></td>
</tr>
</table>
</form>
<br><center><a href="user_options.php"><?php echo func::tr('User options'); ?></a><br><br><a href="index.php"><?php echo func::tr('Login page'); ?></a></center>
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
require ROOT.'include/page/page_foot_codes.php';
?>
</body>
</html>
