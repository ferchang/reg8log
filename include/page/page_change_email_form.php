<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

?>

<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<META HTTP-EQUIV="EXPIRES" CONTENT="0">
<title>Change email</title>
<style>
</style>
<script src="js/forms_common.js"></script>
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

if(!document.change_email_form.password.value) msgs[i++]='Password field is empty!';

newemail_value=document.change_email_form.newemail.value;
reemail_value=document.change_email_form.reemail.value;

if(newemail_value.length<min_length) msgs[i++]="New email is shorter than "+min_length+' characters!';
else if(newemail_value.length>max_length) msgs[i++]="New email is longer than "+max_length+' characters!';
else if(re && newemail_value && !re.test(newemail_value)) msgs[i++]="New email is invalid!";
else if(newemail_value!=reemail_value) msgs[i++]='New email fields are not match!';

if(captcha_exists) validate_captcha(document.change_email_form.captcha.value);

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
<body bgcolor="#D1D1E9" text="#000000" link="#0000FF" vlink="#800080" alink="#FF0000" style="margin: 0" >
<table width="100%"  height="100%" cellpadding="5" cellspacing="0">
<tr>
<td>
<table bgcolor="#7587b0" align="center">
<form name="change_email_form" action="" method="post">
<?php

echo '<input type="hidden" name="antixsrf_token" value="';
echo $_COOKIE['reg8log_antixsrf_token'];
echo '">';

require $index_dir.'include/code/code_generate_form_id.php';

if(isset($err_msgs)) {
echo '<tr align="center"><td colspan="3"  style="border: solid thin yellow; font-style: italic"><span style="color: #800">Errors:</span><br />';
foreach($err_msgs as $err_msg) {
$err_msg[0]=strtoupper($err_msg[0]);
echo "<span style=\"color: yellow\" >$err_msg</span><br />";
}
echo '</td></tr>';
}
?>
<tr>
<td align="right">Your account Password:</td><td><input type="password" name="password" size="30" style="width: 100%"  autocomplete="off" /></td>
</tr>
<tr>
<td align="right">New email:</td><td><input type="text" name="newemail" size="30" style="width: 100%" <?php if(isset($_POST['newemail'])) echo 'value="', htmlspecialchars($_POST['newemail'], ENT_QUOTES, 'UTF-8'), '"'; ?>></td>
</tr>
<tr>
<td align="right">Retype new email:</td><td><input type="text" name="reemail" size="30" style="width: 100%" <?php if(isset($_POST['reemail'])) echo 'value="', htmlspecialchars($_POST['reemail'], ENT_QUOTES, 'UTF-8'), '"'; ?>></td>
</tr>
<?php
if(isset($captcha_needed) and !$captcha_verified) require $index_dir.'include/page/page_captcha_form.php';
?>
<tr>
<td></td><td><span style="color: yellow; font-style: italic" id="cap">&nbsp;</span></td>
</tr>
<tr>
<td></td>
<td align="center"><input type="reset" value="Clear" onClick="return clear_form()" />
<input type="submit" value="Submit" onClick="return validate()" /></td>
</tr>
</table>
</form>
<br><center><a href="user_options.php">User options</a><br><br><a href="index.php">Login page</a></center>
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
