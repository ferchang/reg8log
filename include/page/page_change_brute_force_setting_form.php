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
<title><?php echo func::tr('Change brute-force protection setting'); ?></title>
<script src="js/forms_common.js"></script>
<?php require ROOT.'include/code/code_validate_captcha_field-js.php'; ?>
<script src="js/sha256.js"></script>
<script language="javascript">
function clear_form() {
	document.block_disable_form.password.value='';
	if(captcha_exists) document.getElementById('captcha_check_status').innerHTML='<?php echo func::tr('(Not case-sensitive)'); ?>';
	clear_cap(true);
	return false;
}

<?php
echo "\nsite_salt='".SITE_SALT."';\n";
?>

function hash_password() {
	document.block_disable_form.password.value='hashed-'+site_salt+'-'+hex_sha256(site_salt+document.block_disable_form.password.value);
}

function validate() {//client side validator

clear_cap(true);

msgs=new Array();

i=0;

if(!block_disable_form.password.value.length) msgs[i++]="<?php echo func::tr('password field is empty!'); ?>";

if(captcha_exists) validate_captcha(document.block_disable_form.captcha.value);

if(msgs.length) {
clear_cap(false);
for(i in msgs){
	msgs[i]=msgs[i].charAt(0).toUpperCase()+msgs[i].substring(1, msgs[i].length);
	cap.appendChild(document.createTextNode(msgs[i]));
	cap.appendChild(document.createElement("br"));
}
return false;
}

if(captcha_exists) {
	form_obj=document.block_disable_form;
	check_captcha();
	return false;
}

hash_password();

return true;
}//client side validator

</script>
</head>
<body bgcolor="#D1D1E9" text="#000000" link="#0000FF" vlink="#800080" alink="#FF0000" <?php echo PAGE_DIR; ?>>
<table width="100%" height="100%"><tr><td align="center">
<form name="block_disable_form" action="" method="post">
<table>
<?php if(empty($err_msgs)) { ?>
<tr>
<td align="center">
<table>
<tr>
<td>
<div style="border: thin solid #000; padding: 7px; background: yellow">
<h4 align="center" style="color: red; margin: 5px"><?php echo func::tr('Warning'); ?>!</h4>
<?php echo func::tr('protection change warning msg'); ?>
</div>
</td>
</tr>
</table>
</td>
</tr>
<?php } ?>
<tr><td align="center">
<table bgcolor="#dddddd" style="padding: 5px; border: 2px solid #000; padding: 7px">
<tr><td>
<?php

if(!empty($err_msgs)) {
	echo '<tr align="center"><td colspan="3"  style="border: solid thin orange; font-style: italic; background: #ccc; padding: 7px;"><span style="color: #800">', func::tr('Errors'), ':</span><br />';
	foreach($err_msgs as $err_msg) {
		$err_msg[0]=strtoupper($err_msg[0]);
		echo "<span style=\"color: red\" >$err_msg</span><br />";
	}
	echo '</td></tr><tr><td><div style="height: 10px">&nbsp;</div></td></tr>';
}

echo '<input type="hidden" name="antixsrf_token" value="';
echo ANTIXSRF_TOKEN4POST;
echo '">';

require ROOT.'include/code/code_generate_form_id.php';

?>
<tr>
<td align="left" style="" colspan="3"><b><?php echo func::tr('Protect my account against brute-force attacks with'); ?>:</b>
<select name="disables" >
<?php

if(isset($block_options['account8ip_block'])) echo '<option value="0" style="" ', (isset($disables) and $disables==0)? 'selected':'', '>', func::tr('Account and IP blocks (Maximum protection)'), '</option>';

if(isset($block_options['account_block'])) echo '<option value="', $block_options['account_block'], '" style="" ', (isset($disables) and $disables==$block_options['account_block'])? 'selected':'', '>', func::tr('Account block (Good protection)'), '</option>';

if(isset($block_options['ip_block'])) echo '<option value="', $block_options['ip_block'], '" style="" ', (isset($disables) and $disables==$block_options['ip_block'])? 'selected':'', '>', func::tr('IP block (Weak protection)'), '</option>';

if(isset($block_options['no_block'])) echo '<option value="', $block_options['no_block'],'" style="" ', (isset($disables) and $disables==$block_options['no_block'])? 'selected':'', '>', func::tr('No protection'), '</option>';

?>
</select>
</td>
</tr>
<tr>
<td colspan="3"><b><?php echo func::tr('Current setting'); ?>: </b>
<?php

//====================

$_username2=$identified_user;
require ROOT.'include/code/code_accomodate_block_disable.php';

//=====================

$block_config=0;
if(config::get('ip_block_threshold')!==-1) $block_config+=1;
if(config::get('account_block_threshold')!==-1) $block_config+=2;

//echo $block_config;

if($block_disable==0) switch($block_config) {
	case 0:
		echo '<span style="background: red; padding: 5px;">', func::tr('No protection'), '</span>';
	break;
	case 1:
		echo func::tr('ip block weak');
	break;
	case 2:
		echo func::tr('account block good');
	break;
	case 3:
		echo func::tr('account and ip block max');
	break;
}
else if($block_disable==1) switch($block_config) {
	case 0:
	case 1:
		echo '<span style="background: red; padding: 5px;">', func::tr('No protection'), '</span>';
	break;
	case 2:
	case 3:
		echo func::tr('account block good');
	break;
}
else if($block_disable==2) switch($block_config) {
	case 0:
	case 2:
		echo '<span style="background: red; padding: 5px;">', func::tr('No protection'), '</span>';
	break;
	case 1:
	case 3:
		echo func::tr('ip block weak');
	break;
}
else if($block_disable==3) echo '<span style="background: red; padding: 5px;">', func::tr('No protection'), '</span>';
?>
</tr>
<tr><td align="" style="" colspan="3"><b><?php echo func::tr('Enter your account password'); ?>:</b> <input type="password" name="password">
<input type="text" name="dummy" style="display: none" disabled />
<!-- i added this dummy input because, otherwise, IE8 doesn't execute submit button's onclick when the form is submitted with the Enter key -->
</td></tr>
<?php
if(isset($captcha_needed) and !$captcha_verified) require ROOT.'include/page/page_captcha_form.php';
?>
<tr>
<td align="center" colspan="3"><span style="color: red; font-style: italic" id="cap">&nbsp;</span></td>
</tr>
<tr>
<td align="center" colspan="3"><input type="reset" value="<?php echo func::tr('Clear'); ?>" onClick="return clear_form()" />
<input type="submit" value="<?php echo func::tr('Submit'); ?>" onClick="return validate()" /></td>
</tr></table></td></tr></table>
</form>
<center><a href="user_options.php"><?php echo func::tr('User options'); ?></a><br><br><a href="index.php"><?php echo func::tr('Login page'); ?></a></center>
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
