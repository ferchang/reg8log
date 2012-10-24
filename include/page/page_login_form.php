<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

require_once $index_dir.'include/info/info_lockdown.php';

?>

<html>

<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<META HTTP-EQUIV="EXPIRES" CONTENT="0">
      <title>Login</title>
      <meta http-equiv="generator" content="Kate" />
      <style>

button {
margin-left: 1; margin-right: 1
}

</style>
<script src="js/common.js"></script>
<script src="js/sha256.js"></script>
<script language="javascript">

function clear_form() {
document.login_form.username.value='';
document.login_form.password.value='';
document.login_form.remember.checked=false;
clear_cap(true);
return false;
}

<?php
echo "\nsite_salt='$site_salt';\n";
?>

function hash_password() {
document.login_form.password.value='hashed-'+site_salt+'-'+hex_sha256(site_salt+document.login_form.password.value);
}

function validate()
{
msgs=new Array();
i=0;
if(!document.login_form.username.value) msgs[i++]='Username field is empty!';
if(!document.login_form.password.value) msgs[i++]='Password field is empty!';
if(captcha_exists) validate_captcha(document.login_form.captcha.value);
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

<body bgcolor="#D1D1E9" text="#000000" link="#0000FF" vlink="#800080" alink="#FF0000" style="margin: 0;" >

<table width="100%"  cellpadding="5" cellspacing="0">
<tr>
<td valign="top">
</td>
<td  width="100%" align="left" valign="top">
<?php
require $index_dir.'include/page/page_sections.php';
?>
</td>
</tr>
</table>
<form name="login_form" action="" method="post">
<center>
<table bgcolor="#7587b0">
<?php

echo '<input type="hidden" name="antixsrf_token" value="';
echo $_COOKIE['reg8log_antixsrf_token'];
echo '">';

require $index_dir.'include/code/code_generate_form_id.php';

if(isset($lockdown_bypass_mode)) echo '<tr><td colspan="3" align="center"><div style="color: #fff; border: thick solid orange; padding: 5px; font-size: 13pt; font-weight: bold">Lockdown bypass mode</div></td></tr>';

if(isset($err_msg)) {//a login attempt with login form occured and was unsuccessful; $err_msg contains error message that are to be inserted in top of login form
echo '<tr align="center"><td colspan="3"  style="border: solid thin yellow; font-style: italic"><span style="color: #800">Login error:</span><br />';
echo "<span style=\"color: yellow\" >$err_msg</span><br />";
echo '</td></tr>';
}
if(isset($captcha_msg)) {
echo '<tr align="center"><td colspan="3" style="border: solid thin yellow; font-style: italic">';
echo "<span style=\"color: yellow\" >$captcha_msg</span><br />";
echo '</td></tr>';
}
?>
<tr>
<td align="right">Username:</td><td colspan="2"><input type="text" name="username" maxlength="30" style="width: 100%" <?php if(isset($_POST['username'])) echo 'value="', htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8'), '"'; ?>></td>
</tr>
<tr>
<td align="right">Password:</td><td colspan="2"><input type="password" name="password" maxlength="30" style="width: 100%"  autocomplete="off" /></td>
</tr>
<tr>
<td colspan="3" align="right">Remember me for future sessions: <input type="checkbox" value="true" name="remember" <?php if($remember) echo 'checked'; ?>></td>
</tr>
<!-- -->
<?php
if(isset($captcha_needed) or $captcha_threshold==0) require $index_dir.'include/page/page_captcha_form.php';
?>
<!-- -->
<tr>
<td></td><td colspan="2"><span style="color: yellow; font-style: italic" id="cap">&nbsp;</span></td>
</tr>
<tr>
<td><a href="send_password_reset_link.php" title="Forgot password/username">Forgot password</a></td>
<td align="center" colspan="2"><input type="reset" value="Clear" onClick="return clear_form()" />
<input type="submit" value="Log in" onClick="return validate()" /></td>
</tr>
<?php
if(isset($err_msg) and $lockdown_threshold!=-1 and !isset($captcha_err) and !isset($lockdown_bypass_mode) and !isset($no_pretend_user) and !($block_disable==2 or $block_disable==3)) {
	require_once $index_dir.'include/func/func_duration2msg.php';
	$lockdown_period_msg=duration2friendly_str($lockdown_period, 0);
	$tmp20=$lockdown_threshold-$failed_attempts;
	echo '<tr align="left"><td colspan="3"  style="border: solid thin yellow; font-style: italic">';
	echo "<span style=\"color: #a32\" >Only $lockdown_threshold failed login attempts are permitted in every $lockdown_period_msg.<br>Number of failed login attempts in the past $lockdown_period_msg: $failed_attempts<br>Number of tries left: $tmp20</span>";
	echo '</td></tr>';
}
echo '</table>';
if(isset($lockdown_bypass_mode) and $lockdown_bypass_max_incorrect_logins) echo '<br>Note: Maximum number of incorrect logins is limited to <span style="color: red">', $lockdown_bypass_max_incorrect_logins, '</span>.';
?>
</center>
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
require $index_dir.'include/page/page_foot_codes.php';
?>
<script>
</body>
</html>
