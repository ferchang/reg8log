<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
$parent_page=true;

$index_dir='./';

require $index_dir.'include/common.php';

require $index_dir.'include/code/code_encoding8anticache_headers.php';

require $index_dir.'include/code/code_identify.php';

if(!isset($identified_user)) my_exit('<center><h3>'.tr('You are not authenticated msg').'.</h3><a href="index.php">'.tr('Login page').'</a></center>');

?>

<html <?php echo $page_dir; ?>>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<META HTTP-EQUIV="EXPIRES" CONTENT="0">
<title><?php echo tr('User options'); ?></title>
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
<body bgcolor="#7587b0" text="#000000" link="#0000FF" vlink="#800080" alink="#FF0000" style="margin: 0" <?php echo $page_dir; ?>>
<table width="100%"  cellpadding="5" cellspacing="0">
<tr>
<td valign="top">
</td>
<td  width="100%" valign="top">
<?php
require $index_dir.'include/page/page_sections.php';
?>
</td>
</tr>
</table>
<center>
<table bgcolor="#7587b0">
<tr><td>
<ul>
<li><a class="li_item" href="change_password.php"><?php echo tr('Change password'); ?></a><br>
<li><a class="li_item" href="change_email.php"><?php echo tr('Change email'); ?></a><br>
<?php

if($change_autologin_key_upon_login!=2 and ($allow_manual_autologin_key_change or $identified_user=='Admin')) {
	echo '<li><a class="li_item" href="change_autologin_key.php">', tr("Logging other systems out"), '</a>';
	if(!$allow_manual_autologin_key_change) echo '<small> (', tr('Admin only'), ')</small>';
}

require_once $index_dir.'include/config/config_brute_force_protection.php';
require $index_dir.'include/code/code_check_block_options.php';
if(count($block_options)>1)  {
	echo '<li><a class="li_item" href="change_brute_force_protection_setting.php">', tr('Change brute-force protection setting'), '</a>';
	if(!$allow_users2disable_blocks) echo '<small> (', tr('Admin only'), ')</small>';
}

?>
</ul>
</td></tr>
</table>
</center>
<?php
require $index_dir.'include/page/page_foot_codes.php';
?>
</body>
</html>
