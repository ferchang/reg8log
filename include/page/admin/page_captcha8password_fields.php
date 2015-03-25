<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(isset($captcha_needed) and !$captcha_verified) echo '<table  align=center style="border: thin solid #000; background: #bbb"><tr><td>';
else echo '<table  cellspacing=0 cellpadding=0><tr><td>';

if(isset($password_check_needed)) {
	echo func::tr('Admin password'), ': <input type="password" name="password" size=15>&nbsp;';
	if(config::get('admin_operations_require_password')>1) echo '&nbsp;', func::tr('Remember'), ': <input type=checkbox style="vertical-align: middle" name=remember title="', func::tr('Remember for'), ' ', func::duration2friendly_str(config::get('admin_operations_require_password'), 0), '"', ((isset($_POST['remember']))? ' checked ' : ' '), '>';
	echo '&nbsp;&nbsp;';
}

if(isset($captcha_needed) and !$captcha_verified) {
	echo '</td></tr><tr align=center><td>';
	$captcha_form4login=true;
	require ROOT.'include/page/page_captcha_form.php';
	echo '</td></tr>';
	echo '<tr align=center><td>';
}

if(isset($password_check_needed)) {
	echo '<input type="reset" value="', func::tr('Clear') ,'" onClick="return clear_form()" />', '&nbsp;';
}

?>