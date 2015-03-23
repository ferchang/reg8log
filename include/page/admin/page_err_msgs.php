<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(isset($password_msg)) {
	echo '<table><tr align="center"><td colspan="3" style="border: solid thin yellow; font-style: italic; background: #555; padding: 3px;">';
	echo "<span style=\"color: yellow\" >&nbsp;$password_msg&nbsp;</span><br />";
	echo '</td></tr></table>';
}
else if(isset($captcha_msg) and count($err_msgs)==1) {
	echo '<table><tr align="center"><td colspan="3" style="border: solid thin yellow; font-style: italic; background: #555; padding: 3px;">';
	echo "<span style=\"color: yellow\" >&nbsp;$captcha_msg&nbsp;</span><br />";
	echo '</td></tr></table>';
}
else if(!empty($err_msgs)) {
	echo '<table><tr align="center"><td><div  style="border: solid thin orange; font-style: italic; background: #ccc; padding: 7px;"><span style="color: #800">', func::tr('Errors'), ':</span><br />';
	foreach($err_msgs as $err_msg) {
		$err_msg[0]=strtoupper($err_msg[0]);
		echo "<span style=\"color: red\" >$err_msg</span><br />";
	}
	echo '<div style="height: 10px">&nbsp;</div></div></td></tr></table>';
}

?>
