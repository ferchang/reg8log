<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

require_once ROOT.'include/config/config_brute_force_protection.php';

require_once ROOT.'include/config/config_identify.php';

?>

<html <?php echo $page_dir; ?>>

<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<META HTTP-EQUIV="EXPIRES" CONTENT="0">
      <title><?php echo func::tr('Login'); ?></title>
      <style>

button {
	margin-left: 1; margin-right: 1
}

</style>
<script src="js/forms_common.js"></script>
<?php require ROOT.'include/code/code_validate_captcha_field-js.php'; ?>
<script src="js/sha256.js"></script>

<?php
if($tie_login2ip_option_at_login and ($tie_login2ip==1 or $tie_login2ip==2)) {
	echo "<script>\n";
	if($tie_login2ip==1) echo 'var tie_login2ip=1;';
	else echo 'var tie_login2ip=2;';
	echo "\n</script>\n";
	echo '<script src="js/admin_tie_login2ip.js"></script>';
}
else echo "<script>\nfunction check_admin(val) { }\n</script>\n";

//--------------------------------------------------------------

require_once ROOT.'include/func/func_duration2friendly_str.php';

echo '<script>';

$str='<select name=autologin_age ';
if(count($autologin_ages)==1) $str.=' disabled ';
$str.='>';
foreach($autologin_ages as $value) {
	$str.="<option value=$value style='text-align: center'>";
	if($value==0) $str.=func::tr('Browser session');
	else $str.=duration2friendly_str($value, 0);
}
echo "\nautologin_ages_select_html=\"$str</select>\";";

$str='<select name=autologin_age ';
if(count($admin_autologin_ages)==1) $str.=' disabled ';
$str.='>';
foreach($admin_autologin_ages as $value) {
	$str.="<option value=$value style='text-align: center'>";
	if($value==0) $str.=func::tr('Browser session');
	else $str.=duration2friendly_str($value, 0);
}
echo "\nadmin_autologin_ages_select_html=\"$str</select>\";\n";

echo '</script>';

//-------------------------------------------

?>

<script>

<?php
echo 'var autologin_age_group=';
if(isset($_POST['username']) and strtolower($_POST['username'])=='admin') echo "'admin';\n";
else echo "'users';\n";
?>

function check_autologin_age_options(val) {
	if(val.toLowerCase()=='admin') {
			if(autologin_age_group=='admin') return;
			document.getElementById('autologin_age_select_placeholder').innerHTML=admin_autologin_ages_select_html;
			autologin_age_group='admin';
			return;
	}
	else {
		if(autologin_age_group=='users') return;
		document.getElementById('autologin_age_select_placeholder').innerHTML=autologin_ages_select_html;
		autologin_age_group='users';
		return;
	}

}
</script>

<script language="javascript">

var login2ip_change=false;

function clear_form() {
	document.login_form.username.value='';
	document.login_form.password.value='';
	if(captcha_exists) document.getElementById('captcha_check_status').innerHTML='<?php echo func::tr('(Not case-sensitive)'); ?>';
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

	clear_cap(true);

	msgs=new Array();
	i=0;
	if(!document.login_form.username.value) msgs[i++]='<?php echo func::tr('Username field is empty!'); ?>';
	if(!document.login_form.password.value) msgs[i++]='<?php echo func::tr('Password field is empty!'); ?>';
	if(captcha_exists) validate_captcha(document.login_form.captcha.value);
	if(msgs.length) {
	clear_cap(false);
	for(i in msgs){
	cap.appendChild(document.createTextNode(msgs[i]));
	cap.appendChild(document.createElement("br"));
	}
	return false;
	}

	if(captcha_exists) {
		form_obj=document.login_form;
		check_captcha();
		return false;
	}
	
	hash_password();
	
	return true;
}

//----------------------------------------------

var xhr=null;
var username_change=false;

function create_xhr() {
	var xhr;
	if(window.XMLHttpRequest) xhr = new XMLHttpRequest();
	else if (window.ActiveXObject) xhr = new ActiveXObject("Microsoft.XMLHTTP");

	return xhr;
}

function add_captcha(captcha_html) {
	if(captcha_exists) return;
	document.getElementById('captcha_form_placeholder').innerHTML='<table>'+captcha_html+'</table>';
	captcha_exists=true;
	<?php
	require_once ROOT.'include/config/config_register_fields.php';
	echo "captcha_min_len={$fields['captcha']['minlength']};\n";
	echo "captcha_max_len={$fields['captcha']['maxlength']};\n";
	echo "captcha_re=";
	if($fields['captcha']['js_re']===true) echo $fields['captcha']['php_re'];
	else if($fields['captcha']['js_re']===false) echo 'false';
	else echo $fields['captcha']['js_re'];
	echo ";\n";
	?>
	document.getElementById('re_captcha_msg').style.visibility='visible';
	captcha_img_style=document.getElementById('captcha_image').style;
	captcha_img_style.cursor='hand';
	if(captcha_img_style.cursor!='hand') captcha_img_style.cursor='pointer';
}

function remove_captcha() {
	if(!captcha_exists) return;
	document.getElementById('captcha_form_placeholder').innerHTML='';
	captcha_exists=false;
	if(captcha_client_error) clear_cap(true);
}

function check_captcha_needed(uname) {

<?php
if(
($account_captcha_threshold==0 and $admin_account_captcha_threshold==0)
or
($ip_captcha_threshold==0 and $admin_ip_captcha_threshold==0)
or
($account_captcha_threshold==0 and $admin_ip_captcha_threshold==0)
or 
($ip_captcha_threshold==0 and $admin_account_captcha_threshold==0)
) echo "\nreturn;\n";
?>

if(!username_change) {
	//remove_captcha();
	return;
}

if(!xhr) {
	xhr=create_xhr();
	if(!xhr) return false;
}

xhr.open('POST', 'ajax/check_captcha_needed.php', true);
xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

xhr.onreadystatechange=function() {
	if(xhr.readyState == 4) if(xhr.status == 200) {
		if(xhr.responseText=='n') {
			remove_captcha();
			username_change=false;
		}
		else if(xhr.responseText.indexOf('*add captcha from*')!=-1) {
			add_captcha(xhr.responseText);
			username_change=false;
		}
	}
}

xhr.send('username='+encodeURIComponent(uname)+'&antixsrf_token=<?php echo $_COOKIE['reg8log_antixsrf_token4post']; ?>');

return true;
}
//----------------------------------------------

</script>
</head>

<body bgcolor="#D1D1E9" text="#000000" link="#0000FF" vlink="#800080" alink="#FF0000" style="margin: 0;" <?php echo $page_dir; ?>>

<table width="100%"  cellpadding="5" cellspacing="0">
<tr>
<td valign="top">
</td>
<td  width="100%" valign="top">
<?php
require ROOT.'include/page/page_sections.php';
?>
</td>
</tr>
</table>
<form name="login_form" action="" method="post">
<center>
<table bgcolor="#7587b0" >
<?php

echo '<input type="hidden" name="antixsrf_token" value="';
echo $_COOKIE['reg8log_antixsrf_token4post'];
echo '">';

require ROOT.'include/code/code_generate_form_id.php';

if(isset($block_bypass_mode)) echo '<tr><td colspan="3" align="center"><div style="color: #fff; border: thick solid orange; padding: 5px; font-size: 13pt; font-weight: bold">', func::tr('Block-bypass mode'), '</div></td></tr>';

if(isset($err_msg)) {//a login attempt with login form occured and was unsuccessful; $err_msg contains error message that are to be inserted in top of login form
echo '<tr align="center"><td colspan="3"  style="border: solid thin yellow; font-style: italic"><span style="color: #800">', func::tr('Errors'), ':</span><br />';
echo "<span style=\"color: yellow\" >$err_msg</span><br />";
echo '</td></tr>';
}
if(isset($captcha_msg)) {
echo '<tr align="center"><td colspan="3" style="border: solid thin yellow; font-style: italic">';
echo "<span style=\"color: yellow\" >$captcha_msg</span><br />";
echo '</td></tr>';
}
else if(isset($autologin_age_msg)) {
echo '<tr align="center"><td colspan="3" style="border: solid thin yellow; font-style: italic">';
echo "<span style=\"color: yellow\" >$autologin_age_msg</span><br />";
echo '</td></tr>';
}
?>
<tr>
<td <?php echo $cell_align; ?> ><?php echo func::tr('Username'); ?>:</td><td colspan="2"><input type="text" name="username" maxlength="30" style="width: 100%" <?php if(isset($_POST['username'])) echo 'value="', htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8'), '"'; ?> onchange="check_admin(this.value); username_change=true; check_autologin_age_options(this.value)" onblur="if(this.value!='') check_captcha_needed(this.value);" ></td>
</tr>
<tr>
<td <?php echo $cell_align; ?>><?php echo func::tr('Password'); ?>:</td><td colspan="2"><input type="password" name="password" maxlength="30" style="width: 100%"  autocomplete="off" /></td>
</tr>
<tr>
<td colspan="3" <?php echo $cell_align; ?> align="right"><?php echo func::tr('Remember login for'); ?>: 

<span id=autologin_age_select_placeholder>
<select name=autologin_age 
<?php

require_once ROOT.'include/func/func_autologin_ages.php';
$autologin_ages=get_autologin_ages();

if(count($autologin_ages)==1) echo ' disabled >';
else echo ' >';

if(count($autologin_ages)==1) {
	echo "<option value={$autologin_ages[0]}>";
	if($autologin_ages[0]==0) echo func::tr('Browser session');
	else echo duration2friendly_str($autologin_ages[0], 0);
}
else {
	foreach($autologin_ages as $value) {
		echo "<option value=$value style='text-align: center' ";
		if(isset($_POST['autologin_age']) and $value==$_POST['autologin_age']) echo ' selected ';
		echo '>';
		if($value==0) echo func::tr('Browser session');
		else echo duration2friendly_str($value, 0);
	}
}

?>
</select>
</span>

</td>
</tr>
<?php

if($tie_login2ip_option_at_login) {
	echo "<tr><td colspan=\"3\" $cell_align ";
	echo ' title="', func::tr('tie login to ip option description'), '">', func::tr('Tie my login to my IP address'), ': <input type="checkbox" value="true" name="login2ip" ', ($login2ip or (empty($_POST) and $tie_login2ip>1))? 'checked':'', ' onclick="login2ip_change=true" id="login2ip_checkbox"></td></tr>';
}

echo '<tr><td colspan="3" id="captcha_form_placeholder">';
if(
isset($captcha_needed)
or
($account_captcha_threshold==0 and $admin_account_captcha_threshold==0)
or
($ip_captcha_threshold==0 and $admin_ip_captcha_threshold==0)
or
($account_captcha_threshold==0 and $admin_ip_captcha_threshold==0)
or 
($ip_captcha_threshold==0 and $admin_account_captcha_threshold==0)
) {
	$captcha_form4login=true;
	require ROOT.'include/page/page_captcha_form.php';
}
echo '</td></tr>';

?>
<!-- -->
<tr>
<td></td><td colspan="2"><span style="color: yellow; font-style: italic" id="cap">&nbsp;</span></td>
</tr>
<tr>
<td></td>
<td align="center" colspan="2"><input type="reset" value="<?php echo func::tr('Clear'); ?>" onClick="return clear_form()" />
<input type="submit" value="<?php echo func::tr('Submit'); ?>" onClick="return validate()" /></td>
</tr>
<tr align="center"><td colspan="3"><br><a href="password_reset_request.php" ><?php echo func::tr('Forgot password/username'); ?></a><br><br></td></tr>
<?php
if(isset($err_msg) and $account_block_threshold!=-1 and !isset($captcha_err) and !isset($block_bypass_mode) and !isset($no_pretend_user) and !($block_disable==2 or $block_disable==3)) {
	require_once ROOT.'include/func/func_duration2friendly_str.php';
	$account_block_period_msg=duration2friendly_str($account_block_period, 0);
	$tmp20=$account_block_threshold-$incorrect_attempts;
	echo '<tr ><td colspan="3"  style="border: solid thin yellow; font-style: italic">';
	echo "<span style=\"color: #a32\" >", sprintf(func::tr('login limit warning'), $account_block_threshold, $account_block_period_msg, $account_block_period_msg, $incorrect_attempts, $tmp20), "</span>";
	echo '</td></tr>';
}
echo '</table>';
if(isset($block_bypass_mode) and $block_bypass_max_incorrect_logins) echo '<br>', sprintf(func::tr('block_bypass_mode_max_logins'), $block_bypass_max_incorrect_logins);
?>
</center>
</form>
<script>
//copy the same code into add_captcha JS function
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
<script>
</body>
</html>
