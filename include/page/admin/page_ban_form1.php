<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(isset($ban_page)) $form_color='#f55750';
else $form_color='#55a750';

?>

<html <?php echo $page_dir; ?>>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<META HTTP-EQUIV="EXPIRES" CONTENT="0">
<?php
if(isset($ban_page)) echo '<title>', tr('Ban user'), '</title>';
else echo '<title>', tr('Unban user'), '</title>';
?>
<script src="../js/forms_common.js"></script>
<?php require $index_dir.'include/code/code_validate_captcha_field-js.php'; ?>
<script language="javascript">

function clear_form() {
	document.ban_form.user.value='';
	clear_cap(true);
	return false;
}

function validate() {//client side validator

msgs=new Array();

i=0;

if(!ban_form.user.value.length) msgs[i++]="<?php echo tr('user field is empty!'); ?>";

if(msgs.length) {
clear_cap(false);
for(i in msgs){
	msgs[i]=msgs[i].charAt(0).toUpperCase()+msgs[i].substring(1, msgs[i].length);
	cap.appendChild(document.createTextNode(msgs[i]));
	cap.appendChild(document.createElement("br"));
}
return false;
}

return true;
}//client side validator

</script>
</head>
<body bgcolor="#D1D1E9" text="#000000" link="#0000FF" vlink="#800080" alink="#FF0000" <?php echo $page_dir; ?>>
<table width="100%" height="100%"><tr><td align="center">
<h4 style="margin-bottom: 3px"><?php echo tr('Specify username or uid:'); ?></h4>
<form name="ban_form" action="" method="post">

<?php

echo '<table bgcolor="', $form_color, '" style="padding: 5px">';

if(!empty($err_msgs)) {
	echo '<tr align="center"><td colspan="3"  style="border: solid thin yellow; font-style: italic"><span style="color: #800">', tr('Errors'), ':</span><br />';
	foreach($err_msgs as $err_msg) {
		$err_msg[0]=strtoupper($err_msg[0]);
		echo "<span style=\"color: yellow\" >$err_msg</span><br />";
	}
	echo '</td></tr>';
}

echo '<input type="hidden" name="antixsrf_token" value="';
echo $_COOKIE['reg8log_antixsrf_token'];
echo '">';

require $index_dir.'include/code/code_generate_form_id.php';

?>
<tr>
<td style=""><input type="radio" name="which" value="username" <?php if(isset($_POST['which'])) {
if($_POST['which']=='username') echo ' checked="true" '; } else echo ' checked="true" '; ?>><?php echo tr('username'); ?>&nbsp;&nbsp;<input type="radio" name="which" value="uid" <?php if(isset($_POST['which']) and $_POST['which']=='uid') echo ' checked="true" '; ?>><?php echo tr('uid'); ?></td>
</tr>
<tr>
<td style=""><?php echo tr('User'); ?>:&nbsp;<input type="text" name="user" style="" <?php if(isset($_POST['user'])) echo 'value="', htmlspecialchars($_POST['user'], ENT_QUOTES, 'UTF-8'), '"'; ?> size="30"></td></tr>
<tr>
<td align="center"><span style="color: yellow; font-style: italic" id="cap">&nbsp;</span></td>
</tr>
<tr>
<td align="center">
<input type="reset" value="<?php echo tr('Clear'); ?>" onClick="return clear_form();" tabindex="100" />
<input type="submit" name="ban_form1" value="<?php echo tr('Submit'); ?>" onClick="return validate()" /></td>
</tr></table>
</form>
<a href="index.php"><?php echo tr('Admin operations'); ?></a><br><br>
<a href="../index.php"><?php echo tr('Login page'); ?></a>
</td></tr></table>
<?php
require $index_dir.'include/page/page_foot_codes.php';
?>
</body>
</html>
