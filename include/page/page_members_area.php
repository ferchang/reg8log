<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

?>

<html <?php echo PAGE_DIR; ?>>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<META HTTP-EQUIV="EXPIRES" CONTENT="0">
<title><?php echo func::tr('Members area'); ?></title>
<script src="js/logout.js"></script>
</head>
<body bgcolor="#7587b0" <?php echo PAGE_DIR; ?>>
<table width="100%" height="80%">
<tr>
<td valign="top">
<?php
require ROOT.'include/page/page_sections.php';
?>
</td>
<tr>
<td align="center">
<?php
echo $msg;
require ROOT.'include/page/page_print_last_activity.php';
?>
<br /><a href="logout.php?antixsrf_token=<?php echo ANTIXSRF_TOKEN4GET; ?>" onclick="return onLogout()"><?php echo func::tr('Log out'); ?></a></td>
</tr>
</table>
<?php
require ROOT.'include/page/page_foot_codes.php';
?>
</body>
</html>
