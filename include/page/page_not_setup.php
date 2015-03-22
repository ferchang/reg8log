<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$index_dir=func::get_relative_root_path();

?>

<html <?php echo $page_dir; ?>>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<META HTTP-EQUIV="EXPIRES" CONTENT="0">
<title><?php echo func::tr('No database table'); ?></title>
</head>
<body bgcolor="#7587b0" <?php echo $page_dir; ?>>
<table width="100%" height="100%"><tr><td align="center">
<h3><?php echo func::tr('It seems that database tables are not installed'); ?>.<br><small><a href="<?php echo $index_dir; ?>setup/db_setup.php" style="color: #fff"><?php echo func::tr('Go for installing database tables with the installer'); ?></a>
<br><br><a href="<?php echo $index_dir; ?>change_lang.php?antixsrf_token=<?php echo $_SESSION['reg8log']['antixsrf_token4get']; ?>" onclick="this.href=this.href+'&addr='+location.href"><?php echo func::tr('Change language'); ?></a>
</small></h3>
</td></tr></table>
<?php
require ROOT.'include/page/page_foot_codes.php';
?>
</body>
</html>
