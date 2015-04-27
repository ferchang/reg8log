<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

require ROOT.'include/code/sess/code_sess_start.php';

$captcha_verified=isset($_SESSION['reg8log']['captcha_verified']);

?>

<html <?php echo PAGE_DIR; ?>>

<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<META HTTP-EQUIV="EXPIRES" CONTENT="0">
<title><?php echo func::tr('Register'); ?></title>
<script src="js/forms_common.js"></script>
<?php require ROOT.'include/code/code_validate_captcha_field-js.php'; ?>
<script src="js/sha256.js"></script>
<script language="javascript">

function clear_form() {
document.register_form.username.value='';
document.register_form.password.value='';
document.register_form.repass.value='';
document.register_form.email.value='';
document.register_form.reemail.value='';
document.register_form.gender[0].checked=true;
if(captcha_exists) document.getElementById('captcha_check_status').innerHTML='<?php echo func::tr('(Not case-sensitive)'); ?>';
clear_cap(true);
if(xhr) {
	xhr.abort();
	report(ws, '');
}
return false;
}

<?php
echo "fields=new Array(\n";
$f=false;
foreach($_fields as $field_name=>$specs)
if($specs['client_validate']) {
  if($f) echo ",\n";
  else $f=true;
echo "new Array(\n'$field_name',\n{$specs['minlength']},\n{$specs['maxlength']},\n";
if($specs['js_re']===true) echo $specs['php_re'];
else if($specs['js_re']===false) echo 'false';
else echo $specs['js_re'];
if(config::get('lang')=='fa') echo ",\n'", func::tr($field_name), "'";
else echo ",\n'$field_name'";
echo "\n)";
}
echo "\n);\n";

echo "\nsite_salt='$site_salt';\n";
?>

var auto_filled=false;
var password_value='';
var password_edited=false;

function hash_password() {
	if(document.register_form.password.value.indexOf('encrypted-'+site_salt)==0 || document.register_form.password.value.indexOf('hashed-'+site_salt)==0) return;
	document.register_form.repass.value=document.register_form.password.value='hashed-'+site_salt+'-'+hex_sha256(site_salt+document.register_form.password.value);
}

password_autofill_msg_flag=false;

function password_focus(p, i) {
	if(p.value.indexOf('encrypted-'+site_salt)==0 || p.value.indexOf('hashed-'+site_salt)==0) {
		if(!password_autofill_msg_flag) {
			alert('<?php echo func::tr('password_autofill_msg'); ?>');
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
	else if(i==1 && document.register_form.repass.value.indexOf('encrypted-'+site_salt)==0 || document.register_form.repass.value.indexOf('hashed-'+site_salt)==0) document.register_form.repass.value='';
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

function validate() {//client side validator

clear_cap(true);

msgs=new Array();
i=0;
for(j in fields) {
field_name=fields[j][0];
if(field_name=='captcha') {
if(!captcha_exists) continue;
validate_captcha(document.register_form.captcha.value);
continue;
}

field_value=eval('register_form.'+field_name+'.value');
min_length=fields[j][1];
max_length=fields[j][2];
re=fields[j][3];
locale_field_name=fields[j][4];

if(field_name=='password' && (field_value.indexOf('encrypted-'+site_salt)==0 || field_value.indexOf('hashed-'+site_salt)==0)) {
	if(register_form.password.value!=document.getElementById('repass').value)
	msgs[i++]="<?php echo func::tr('Password fields aren\'t match!'); ?>";
	continue;
}

if(field_value.length<min_length) msgs[i++]=locale_field_name+"<?php echo func::tr(' is shorter than "+min_length+" characters!'); ?>";
else if(field_value.length>max_length) msgs[i++]=locale_field_name+"<?php echo func::tr(' is longer than "+max_length+" characters!'); ?>";
else if(re && field_value && !re.test(field_value)) msgs[i++]=locale_field_name+"<?php echo func::tr(' is invalid!'); ?>";
else if(field_name=='email' && register_form.email.value!=register_form.reemail.value)
msgs[i++]="<?php echo func::tr('Email fields aren\'t match!'); ?>";
else if(field_name=='password' && register_form.password.value!=document.getElementById('repass').value)
msgs[i++]="<?php echo func::tr('Password fields aren\'t match!'); ?>";
}

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
	form_obj=document.register_form;
	check_captcha();
	return false;
}

hash_password();

return true;
}//client side validator

//ajax for checking username availibility

var ws='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

var xhr=null;

function create_xhr() {
	var xhr;
	if(window.XMLHttpRequest) xhr = new XMLHttpRequest();
	else if (window.ActiveXObject) xhr = new ActiveXObject("Microsoft.XMLHTTP");

	return xhr;
}

var username_change=false;
var report_args={'1':ws, '2':''};

function check_username(uname) {

<?php
if(!config::get('ajax_check_username')) echo "
//ajax username check is disabled on the server!
return;
//ajax username check is disabled on the server!\n\n";
?>

if(!username_change) {
	report(report_args['1'], report_args['2']);
	return;
}

for(j in fields) {
	if(fields[j][0]!='username') continue;
	field_name=fields[j][0];
	field_value=eval('register_form.'+field_name+'.value');
	min_length=fields[j][1];
	max_length=fields[j][2];
	re=fields[j][3];
	invalid=false;
	if(field_value.length<min_length) invalid=true;
	else if(field_value.length>max_length) invalid=true;
	else if(re && field_value && !re.test(field_value)) invalid=true;
	if(invalid) {
		report('&nbsp;&nbsp;<?php echo func::tr('Invalid'); ?>!&nbsp;&nbsp;', 'red');
		return;
	}
}

if(!xhr) {
	xhr=create_xhr();
	if(!xhr) return false;
}

xhr.open('POST', 'ajax/check_username_availability.php', true);
xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

xhr.onreadystatechange=function() {
	//alert(xhr.responseText);
	if(xhr.readyState == 4) if(xhr.status == 200) {
	if(xhr.responseText=='y') {
		report('<?php echo func::tr('Not available!'); ?>', 'orange');
		username_change=false;
	}
	else if(xhr.responseText=='n') {
		report('<?php echo func::tr('Is available.'); ?>', 'green');
		username_change=false;
	}
	else if(xhr.responseText=='i') report('&nbsp;&nbsp;<?php echo func::tr('Invalid'); ?>!&nbsp;&nbsp;', 'red');
	else report(ws, '');
	} else report(ws, '');
}

xhr.send('value='+encodeURIComponent(uname)+'&antixsrf_token=<?php echo ANTIXSRF_TOKEN4POST; ?>');

return true;
}

function report(val, bg) {
	if(val=='onkeypress') val=ws;
	else report_args={'1':val, '2':bg};
	report_field.innerHTML=val;
	if(bg) report_field.style.background=bg;
	else report_field.style.background='brown';
}

//end ajax

</script>
</head>

<body bgcolor="#D1D1E9" text="#000000" link="#0000FF" vlink="#800080" alink="#FF0000" <?php echo PAGE_DIR; ?>>
<table width="100%" height="100%"><tr><td align="center">

<form name="register_form" action="" method="post">
<table bgcolor="#7587b0" >

<?php
if($err_msgs) {
echo '<tr align="center"><td colspan="3"  style="border: solid thin yellow; font-style: italic"><span style="color: #800">', func::tr('Errors'), ':</span><br />';
foreach($err_msgs as $err_msg) {
$err_msg[0]=strtoupper($err_msg[0]);
echo "<span style=\"color: yellow\" >$err_msg</span><br />";
}
echo '</td></tr>';
}

echo '<input type="hidden" name="antixsrf_token" value="';
echo ANTIXSRF_TOKEN4POST;
echo '">';

require ROOT.'include/code/code_generate_form_id.php';

?>
<tr><td <?php echo CELL_ALIGN; ?> valign="top"><?php echo func::tr('Username format'); ?>:</td><td  colspan="2"><?php echo func::tr('username format msg'); ?></td></tr>
<tr>
<td <?php echo CELL_ALIGN; ?> style=""><?php echo func::tr('Username'); ?>:</td>
<td style=""><input
onkeypress="report('onkeypress', '');"
onblur="if(this.value!='') check_username(this.value);" type="text" name="username" style="" <?php if(isset($_POST['username'])) echo 'value="', htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8'), '"'; ?> size="30" onchange="username_change=true;"></td>
<td>
<div id="report_field" style="background: brown; border: thin solid black; padding: 0 2pt 0 2pt"></div>
</td>
</tr>
<tr>
<td <?php echo CELL_ALIGN; ?>><?php echo func::tr('Password'); ?>:</td>
<td colspan="2">
<input type="password" name="password" autocomplete="off" onfocus="password_focus(this, 1);" onblur="password_blur(this, 1);" onkeydown="password_keydown(event);"
<?php
if(isset($_POST['password']) and $_POST['password']!=='' and config::get('password_refill') and !isset($password_error)) {
	$refill=$_POST['password'];
	require ROOT.'include/code/code_refill_password.php';
}
?>
size="30"></td>
</tr>
<tr>
<td <?php echo CELL_ALIGN; ?>><?php echo func::tr('Retype password'); ?>:</td>
<td colspan="2">
<input type="password" id="repass" name="repass" autocomplete="off" style="" onfocus="password_focus(this, 2);" onblur="password_blur(this, 2);" onkeydown="password_keydown(event);"
<?php
if(isset($refill_output)) echo $refill_output;
?>
size="30"></td>
</tr>
<tr>
<td <?php echo CELL_ALIGN; ?>><?php echo func::tr('Email'); ?>:</td>
<td colspan="2"><input type="text" name="email" style="" <?php if(isset($_POST['email'])) echo 'value="', htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8'), '"'; ?> size="40"></td>
</tr>
<tr>
<td <?php echo CELL_ALIGN; ?>><?php echo func::tr('Retype email'); ?>:</td>
<td colspan="2"><input type="text" name="reemail" style="" <?php if(isset($_POST['reemail'])) echo 'value="', htmlspecialchars($_POST['reemail'], ENT_QUOTES, 'UTF-8'), '"'; ?> size="40"></td>
</tr>
<tr>
<td <?php echo CELL_ALIGN; ?> valign="top"><?php echo func::tr('Gender'); ?>:</td><td colspan="2">
&nbsp;&nbsp;<input type="radio" name="gender" value="n" <?php if(isset($_POST['gender'])) {
if($_POST['gender']==='n') echo ' checked="true" '; } else echo ' checked="true" '; ?>><?php echo func::tr('Not specified'); ?>
&nbsp;&nbsp;<input type="radio" name="gender" value="m" <?php if(isset($_POST['gender']) and $_POST['gender']==='m') echo ' checked="true" '; ?>><?php echo func::tr('Male'); ?>
&nbsp;&nbsp;<input type="radio" name="gender" value="f" <?php if(isset($_POST['gender']) and $_POST['gender']==='f') echo ' checked="true" '; ?>><?php echo func::tr('Female'); ?>
</td>
</tr>
<?php
if(!$captcha_verified) require ROOT.'include/page/page_captcha_form.php';
?>
<tr>
<script language="javascript">

var report_field=document.getElementById('report_field');
report(ws, '');

if(captcha_exists) {
	document.getElementById('re_captcha_msg').style.visibility='visible';
	captcha_img_style=document.getElementById('captcha_image').style;
	captcha_img_style.cursor='hand';
	if(captcha_img_style.cursor!='hand') captcha_img_style.cursor='pointer';
}

</script>
<?php
if(config::get('admin_confirmation_needed') and config::get('can_notify_user_about_admin_action')) {
	echo "<tr><td colspan=\"2\" ".CELL_ALIGN.">";
	echo func::tr('notify me admin action msg'), ': ';
	echo '</td><td><input type="checkbox" name="notify" ';
	if(empty($_POST) or isset($_POST['notify'])) echo ' checked="true" ';
	echo '></td></tr>';
}
?>
<tr>
<td>&nbsp;</td><td colspan="2"><span style="color: yellow; font-style: italic" id="cap">&nbsp;</span></td>
</tr>
<tr>
<td><a href="index.php"><?php echo func::tr('Login page'); ?></a></td>
<td colspan="2">
<input type="reset" value="<?php echo func::tr('Clear'); ?>" onClick="return clear_form();" />
<input type="submit" value="<?php echo func::tr('Submit'); ?>" onClick="return validate()" /></td>
</tr></table>
</form>
</td></tr></table>
<?php
require ROOT.'include/page/page_foot_codes.php';
?>
</body>
</html>
