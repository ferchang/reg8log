<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
$parent_page=true;

$index_dir='../';

$store_request_entropy_probability2=1;

require_once $index_dir.'include/common.php';

require $index_dir.'include/code/code_encoding8anticache_headers.php';

require $index_dir.'include/code/code_require_admin.php';

?>

<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<META HTTP-EQUIV="EXPIRES" CONTENT="0">
<title>Admin operations</title>
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
<body bgcolor="#7587b0" text="#000000" link="#0000FF" vlink="#800080" alink="#FF0000" style="margin: 0;">
<table width="100%"  cellpadding="5" cellspacing="0" style="">
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
<table bgcolor="#7587b0" style="position: relative; top: -150px;">
<tr><td>
<ul>
<li>Accounts:
<ul>
<li><a class="li_item" href="admin-accounts.php">Accounts</a>
<br><br>
<li><a class="li_item" href="admin-ban_user.php">Ban a user</a>
<li><a class="li_item" href="admin-unban_user.php">Unban a user</a>
<li><a class="li_item" href="admin-banned_users.php">Banned users</a>
</ul>
<br>
<li>Pending accounts:
<ul>
<li><a class="li_item" href="admin-pending_accounts.php">Pending accounts</a>
</ul>
<br>
<li>Security logs:
<ul>
<li><a class="li_item" href="admin-blocked_accounts.php">Account blocks</a>
<li><a class="li_item" href="admin-blocked_ips.php">IP blocks</a>
</ul>
</ul>
</td></tr>
</table>
</center>
<?php
require $index_dir.'include/page/page_foot_codes.php';
?>
</body>
</html>
