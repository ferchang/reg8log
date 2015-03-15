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
<title><?php echo func::tr('Reset password'); ?></title>
<style>
</style>
<script src="js/forms_common.js"></script>
<?php require ROOT.'include/code/code_validate_captcha_field-js.php'; ?>
<script src="js/sha256.js"></script>
<script language="javascript">
function clear_form() {
document.reset_pass_form.newpass.value='';
document.reset_pass_form.repass.value='';
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

function hash_password() {
document.reset_pass_form.newpass.value=document.reset_pass_form.repass.value='hashed-'+site_salt+'-'+hex_sha256(site_salt+document.reset_pass_form.newpass.value);
}

function validate()
{

clear_cap(true);

msgs=new Array();
i=0;

newpass_value=document.reset_pass_form.newpass.value;
repass_value=document.reset_pass_form.repass.value;

if(newpass_value.length<min_length) msgs[i++]="<?php echo func::tr('New password is shorter than "+min_length+" characters!'); ?>";
else if(newpass_value.length>max_length) msgs[i++]="<?php echo func::tr('New password is longer than "+max_length+" characters!'); ?>";
else if(re && newpass_value && !re.test(newpass_value)) msgs[i++]="<?php echo func::tr('New password is invalid!'); ?>";
else if(newpass_value!=repass_value) msgs[i++]='<?php echo func::tr('New password fields are not match!'); ?>';

if(msgs.length) {
clear_cap(false);
for(i in msgs){
cap.appendChild(document.createTextNode(msgs[i]));
cap.appendChild(document.createElement("br"));
}
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
<form name="reset_pass_form" action="" method="post">
<?php

echo '<input type="hidden" name="antixsrf_token" value="';
echo $_SESSION['reg8log']['reg8log_antixsrf_token4post'];
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
<td  <?php echo $cell_align; ?> ><?php echo func::tr('New password'); ?>:</td><td><input type="password" name="newpass" size="30" style="width: 100%"  autocomplete="off" /></td>
</tr>
<tr>
<td  <?php echo $cell_align; ?> ><?php echo func::tr('Retype new Password'); ?>:</td><td><input type="password" name="repass" size="30" style="width: 100%"  autocomplete="off" /></td>
</tr>
<tr>
<td></td><td><span style="color: yellow; font-style: italic" id="cap">&nbsp;</span></td>
</tr>
<tr>
<td></td>
<td align="center"><input type="reset" value="<?php echo func::tr('Clear'); ?>" onClick="return clear_form()" />
<input type="submit" value="<?php echo func::tr('Submit'); ?>" onClick="return validate()" /></td>
</tr>
</table>
</form>
<br><center><a href="index.php"><?php echo func::tr('Login page'); ?></a></center>
</td></tr></table>
<?php
require ROOT.'include/page/page_foot_codes.php';
?>
</body>
</html>
