<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
define('CAN_INCLUDE', true);

require 'include/common.php';

require ROOT.'include/code/code_identify.php';

if(!isset($identified_user)) func::my_exit('<center><h3>'.func::tr('You are not authenticated msg').'.</h3><a href="index.php">'.func::tr('Login page').'</a></center>');

?>

<html <?php echo PAGE_DIR; ?>>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<META HTTP-EQUIV="EXPIRES" CONTENT="0">
<title><?php echo func::tr('User options'); ?></title>
<style>
li {
font-size: large;
margin: 7px;
}
.li_item {
font-size: large;
margin: 7px;
color: white;
}
</style>
</head>
<body bgcolor="#7587b0" text="#000000" link="#0000FF" vlink="#800080" alink="#FF0000" style="margin: 0" <?php echo PAGE_DIR; ?>>
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
<center>
<table bgcolor="#7587b0">
<tr><td>
<ul>
<li><a class="li_item" href="account_info.php"><?php echo func::tr('View account information'); ?></a><br>
<li><a class="li_item" href="change_password.php"><?php echo func::tr('Change password'); ?></a><br>
<li><a class="li_item" href="change_email.php"><?php echo func::tr('Change email'); ?></a><br>
<?php

if($identified_user==='Admin') config::set('change_autologin_key_upon_login', config::get('admin_change_autologin_key_upon_login'));
if(config::get('change_autologin_key_upon_login')!==2 and (config::get('allow_manual_autologin_key_change') or $identified_user==='Admin')) {
	echo '<li><a class="li_item" href="change_autologin_key.php">', func::tr('Logging other systems out'), '</a>';
	if(!config::get('allow_manual_autologin_key_change')) echo '<small> (', func::tr('Admin only'), ')</small>';
}

require ROOT.'include/code/code_check_block_options.php';
if(count($block_options)>1)  {
	echo '<li><a class="li_item" href="change_brute_force_protection_setting.php">', func::tr('Change brute-force protection setting'), '</a>';
	if(!config::get('allow_users2disable_blocks')) echo '<small> (', func::tr('Admin only'), ')</small>';
}

?>
</ul>
</td></tr>
</table>
</center>
<?php
require ROOT.'include/page/page_foot_codes.php';
?>
</body>
</html>
