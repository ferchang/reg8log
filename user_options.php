<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
$parent_page=true;

$index_dir='';

require $index_dir.'include/common.php';

require $index_dir.'include/code/code_encoding8anticache_headers.php';

require $index_dir.'include/code/code_identify.php';

if(!isset($identified_user)) exit('<center><h3>You are not authenticated! <br>First log in.</h3><a href="index.php">Login page</a></center>');

?>

<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<META HTTP-EQUIV="EXPIRES" CONTENT="0">
<title>Control panel</title>
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
<body bgcolor="#7587b0" text="#000000" link="#0000FF" vlink="#800080" alink="#FF0000" style="margin: 0" >
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
<center>
<table bgcolor="#7587b0">
<tr><td>
<ul>
<li><a class="li_item" href="change_password.php">Change password</a><br>
<li><a class="li_item" href="change_email.php">Change email</a>
<?php

require_once $index_dir.'include/info/info_brute_force_protection.php';
require $index_dir.'include/code/code_check_block_options.php';
if(count($block_options)>1)  {
	echo '<li><a class="li_item" href="change_brute_force_protection_setting.php">Change brute-force protection setting</a>';
	if(!$allow_users2disable_blocks) echo '<small> (Admin only)<small>';
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
