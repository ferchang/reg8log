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
<title><?php echo tr('No database table'); ?></title>
</head>
<body bgcolor="#7587b0" <?php echo $page_dir; ?>>
<table width="100%" height="100%"><tr><td align="center">
<h3><?php echo tr('It seems that database tables are not installed'); ?>.<br><small><a href="<?php echo $index_dir; ?>setup/db_setup.php" style="color: #fff"><?php echo tr('Go for installing database tables with the installer'); ?></a>
<br><br><a href="<?php echo $index_dir; ?>change_lang.php?setup&antixsrf_token=<?php echo $_COOKIE['reg8log_antixsrf_token4get']; ?>" onclick="this.href=this.href+'&addr='+location.href"><?php echo tr('Change language'); ?></a>
</small></h3>
</td></tr></table>
<?php
require $index_dir.'include/page/page_foot_codes.php';
?>
</body>
</html>
