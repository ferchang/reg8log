<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

?>

<html <?php echo $page_dir; ?>>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<META HTTP-EQUIV="EXPIRES" CONTENT="0">
<title><?php echo tr('Members area'); ?></title>
<script src="js/logout.js"></script>
</head>
<body bgcolor="#7587b0" <?php echo $page_dir; ?>>
<table width="100%" height="80%">
<tr>
<td valign="top">
<?php
require $index_dir.'include/page/page_sections.php';
?>
</td>
<tr>
<td align="center">
<?php
echo $msg;
require $index_dir.'include/page/page_print_last_activity.php';
?>
<br /><a href="logout.php?antixsrf_token=<?php echo $_COOKIE['reg8log_antixsrf_token']; ?>" onclick="return onLogout()"><?php echo tr('Log out'); ?></a></td>
</tr>
</table>
<?php
require $index_dir.'include/page/page_foot_codes.php';
?>
</body>
</html>
