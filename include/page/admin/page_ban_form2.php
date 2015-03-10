<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

define('CAN_INCLUDE', true);

?>

<html <?php echo $page_dir; ?>>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<META HTTP-EQUIV="EXPIRES" CONTENT="0">
<title><?php echo func::tr('Ban user'); ?></title>
<style>
.unit {
	color: #8fd;
}
</style>
<script src="../js/forms_common.js"></script>
<?php require ROOT.'include/code/code_validate_captcha_field-js.php'; ?>
<script src="../js/sha256.js"></script>
<script language="javascript">

<?php
echo "\nsite_salt='$site_salt';\n";
?>

<?php
if(isset($password_check_needed)) echo 'password_exists=true;';
else echo 'password_exists=false;';
?>

function show_duration_selects(show) {
	if(show==0) document.getElementById('ban_duration').style.visibility='hidden';
	else document.getElementById('ban_duration').style.visibility='visible';
}

function clear_form() {
	//--------------
	if(password_exists) document.ban_form2.password.value='';
	if(document.ban_form2.remember) document.ban_form2.remember.checked=false;
	if(captcha_exists) document.getElementById('captcha_check_status').innerHTML='<?php echo func::tr('(Not case-sensitive)'); ?>';
	//--------------
	ban_form2.reason.value='';
	ban_form2.ban_type[0].click();
	ban_form2.years.options[0].selected=true;
	ban_form2.months.options[0].selected=true;
	ban_form2.days.options[0].selected=true;
	ban_form2.hours.options[0].selected=true;
	clear_cap(true);
	return false;
}

//----------------------

function hash_password() {
	document.ban_form2.password.value='hashed-'+site_salt+'-'+hex_sha256(site_salt+document.ban_form2.password.value);
}

//----------------------

function validate() {//client side validator
	
	clear_cap(true);
	
	msgs=new Array();

	i=0;

	if(ban_form2.years.options[0].selected && ban_form2.months.options[0].selected && ban_form2.days.options[0].selected && ban_form2.hours.options[0].selected && !ban_form2.ban_type[1].checked) msgs[i++]="<?php echo func::tr('no ban duration specified!'); ?>";

	//----------------
	
	if(password_exists) if(!document.ban_form2.password.value.length) msgs[i++]="<?php echo func::tr('password field is empty!'); ?>";

	if(captcha_exists) validate_captcha(document.ban_form2.captcha.value);
	
	//----------------
	
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
		form_obj=document.ban_form2;
		check_captcha();
		return false;
	}

	if(password_exists) hash_password();

	return true;
}//client side validator

</script>
</head>
<body bgcolor="#D1D1E9" text="#000000" link="#0000FF" vlink="#800080" alink="#FF0000" <?php echo $page_dir; ?>>
<table width="100%" height="100%"><tr><td align="center">
<form name="ban_form2" action="" method="post">
<table bgcolor="#7587b0" style="padding: 5px">
<?php
//--------------
if(isset($password_msg)) {
	echo '<tr align="center"><td colspan="3"  style="border: solid thin yellow; font-style: italic;">';
	echo "<span style=\"color: yellow\" >&nbsp;$password_msg&nbsp;</span><br />";
	echo '</td></tr>';
}
else if(isset($captcha_msg) and count($err_msgs)==1) {
	echo '<tr align="center"><td colspan="3"  style="border: solid thin yellow; font-style: italic;">';
	echo "<span style=\"color: yellow\" >&nbsp;$captcha_msg&nbsp;</span><br />";
	echo '</td></tr>';
}
else if(!empty($err_msgs)) {
	echo '<tr align="center"><td colspan="3"  style="border: solid thin yellow; font-style: italic;"><span style="color: #800">', func::tr('Errors'), ':</span><br />';
	foreach($err_msgs as $err_msg) {
		$err_msg[0]=strtoupper($err_msg[0]);
		echo "<span style=\"color: yellow\" >$err_msg</span><br />";
	}
	echo '</td></tr>';
}
//--------------
?>
<?php

/* if(!empty($err_msgs)) {
	echo '<tr align="center"><td colspan="3"  style="border: solid thin yellow; font-style: italic;"><span style="color: #800">', func::tr('Errors'), ':</span><br />';
	foreach($err_msgs as $err_msg) {
		$err_msg[0]=strtoupper($err_msg[0]);
		echo "<span style=\"color: yellow\" >$err_msg</span><br />";
	}
	echo '</td></tr>';
} */

echo '<input type="hidden" name="antixsrf_token" value="';
echo $_COOKIE['reg8log_antixsrf_token4post'];
echo '">';

require ROOT.'include/code/code_generate_form_id.php';

?>

<tr align="center"><td>
<table border style="margin-top: 7px">
<tr style="background: brown; color: #fff"><th><?php echo func::tr('Username'); ?></th><th><?php echo func::tr('Uid'); ?></th><th><?php echo func::tr('Email'); ?></th><th><?php echo func::tr('Gender'); ?></th><th><?php echo func::tr('Member for'); ?></th></tr><tr style="background: #ccc" align="center">
<?php
echo '<td>', htmlspecialchars($rec['username'], ENT_QUOTES, 'UTF-8'), '</td>';
echo '<input type="hidden" name="username" value="', htmlspecialchars($rec['username'], ENT_QUOTES, 'UTF-8'), '">';
echo '<td>', $rec['uid'], '</td>';
echo '<td>', $rec['email'], '</td>';
echo '<td>';
if($rec['gender']=='n') echo '?';
else if($rec['gender']=='m') echo func::tr('Male');
else echo func::tr('Female');
echo '</td>';
require_once ROOT.'include/func/func_duration2friendly_str.php';
echo '<td>', duration2friendly_str($req_time-$rec['timestamp']), '</td>';
?>
</tr></table><br>
</td></tr>
<tr>
<td style=""><?php echo func::tr('Ban for a'); ?> <span class="unit"><?php echo func::tr('duration'); ?></span> <input type="radio" name="ban_type" value="duration" onclick="show_duration_selects(1)" <?php if(isset($_POST['ban_type'])) {
if($_POST['ban_type']=='duration') echo ' checked="true" '; } else echo ' checked="true" '; ?>> <?php echo func::tr('or'); ?> <span class="unit"><?php echo func::tr('infinitely'); ?></span> <input type="radio" name="ban_type" value="infinite" onclick="show_duration_selects(0)" <?php if(isset($_POST['ban_type']) and $_POST['ban_type']=='infinite') echo ' checked="true" '; ?>><br><br>
</td>
</tr>
<tr id="ban_duration" style="display: inline;<?php if(isset($_POST['ban_type']) and $_POST['ban_type']=='infinite') echo ' visibility: hidden'; ?>">
<td align="left"><?php echo func::tr('Ban for '); ?> 
<select name="years"><option>0</option><option <?php if(isset($_POST['years']) and $_POST['years']=='1') echo 'selected'; ?>>1</option></select> <span class="unit"><?php echo func::tr('year(s)'); ?></span> <?php echo func::tr('and'); ?> 
<select name="months"><option>0</option><option <?php if(isset($_POST['months']) and $_POST['months']=='1') echo 'selected'; ?>>1</option><option <?php if(isset($_POST['months']) and $_POST['months']=='2') echo 'selected'; ?>>2</option><option <?php if(isset($_POST['months']) and $_POST['months']=='3') echo 'selected'; ?>>3</option><option <?php if(isset($_POST['months']) and $_POST['months']=='6') echo 'selected'; ?>>6</option><option <?php if(isset($_POST['months']) and $_POST['months']=='9') echo 'selected'; ?>>9</option></select> <span class="unit"><?php echo func::tr('month(s)'); ?></span> <?php echo func::tr('and'); ?> 
<select name="days"><option>0</option><option<?php if(isset($_POST['days']) and $_POST['days']=='1') echo 'selected'; ?>>1</option><option <?php if(isset($_POST['days']) and $_POST['days']=='2') echo 'selected'; ?>>2</option><option <?php if(isset($_POST['days']) and $_POST['days']=='3') echo 'selected'; ?>>3</option><option <?php if(isset($_POST['days']) and $_POST['days']=='7') echo 'selected'; ?>>7</option><option <?php if(isset($_POST['days']) and $_POST['days']=='15') echo 'selected'; ?>>15</option><option <?php if(isset($_POST['days']) and $_POST['days']=='25') echo 'selected'; ?>>25</option></select> <span class="unit"><?php echo func::tr('day(s)'); ?></span> <?php echo func::tr('and'); ?> 
<select name="hours"><option>0</option><option <?php if(isset($_POST['hours']) and $_POST['hours']=='1') echo 'selected'; ?>>1</option><option <?php if(isset($_POST['hours']) and $_POST['hours']=='3') echo 'selected'; ?>>3</option><option <?php if(isset($_POST['hours']) and $_POST['hours']=='6') echo 'selected'; ?>>6</option><option <?php if(isset($_POST['hours']) and $_POST['hours']=='12') echo 'selected'; ?>>12</option><option <?php if(isset($_POST['hours']) and $_POST['hours']=='20') echo 'selected'; ?>>20</option></select> <span class="unit"><?php echo func::tr('hour(s)'); ?></span>
</td>
</tr>
<tr>
<td align=""><br><?php echo func::tr('Reason'); ?>: <input type="text" name="reason" size="50" <?php if(isset($_POST['reason'])) echo 'value="', htmlspecialchars($_POST['reason'], ENT_QUOTES, 'UTF-8'), '"'; ?>></td>
</tr>
<tr>
<td align=""><br>
<table width=100%>
<?php
//--------------------

if(isset($password_check_needed)) {
	echo '<tr><td>';
	echo func::tr('Admin password'), ': <input type="password" name="password" size=15>&nbsp;';
	if($admin_operations_require_password>1) echo '&nbsp;', func::tr('Remember'), ': <input type=checkbox style="vertical-align: middle" name=remember title="', func::tr('Remember for'), ' ', duration2friendly_str($admin_operations_require_password, 0), '"', ((isset($_POST['remember']))? ' checked ' : ' '), '>';
	echo '</td></tr>';
}

if(isset($captcha_needed) and !$captcha_verified) {
	echo '<tr align=center><td>';
	$captcha_form4login=true;
	require ROOT.'include/page/page_captcha_form.php';
	echo '</td></tr>';
}

//---------------------
?>
<tr><td align=center>
<?php
if(isset($password_check_needed)) echo '<br>';
?>
<input type="submit" value="<?php echo func::tr('Cancel'); ?>" name="cancel" />
<input type="reset" value="<?php echo func::tr('Clear'); ?>" onClick="return clear_form();"  />
<input type="submit" value="<?php echo func::tr('Ban'); ?>" name="ban_form2" onClick="return validate()" /></td>
</td></tr></table></tr>
<tr><td align="center"><span style="color: yellow; font-style: italic" id="cap">&nbsp;</span></td>
</tr>
</table>
</form>
<a href="index.php"><?php echo func::tr('Admin operations'); ?></a><br><br>
<a href="../index.php"><?php echo func::tr('Login page'); ?></a>
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
