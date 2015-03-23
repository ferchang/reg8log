<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

?>

<html <?php echo $page_dir; ?>>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<META HTTP-EQUIV="EXPIRES" CONTENT="0">
<title><?php echo func::tr('DB setup - Step 1'); ?></title>
</head>
<body bgcolor="#7587b0" <?php echo $page_dir; ?>>
<table width="100%" height="100%"><tr><td align="center">
<form action="" method="post">
<?php
echo '<input type="hidden" name="antixsrf_token" value="';
echo $_SESSION['reg8log']['antixsrf_token4post'];
echo '">';
?>
<h4><?php echo func::tr('setup - Copy the string msg');?>.</h4>
<input type="text" value="<?php echo $setup_key; ?>" size="30" onfocus="this.select();" style="text-align: center" readonly id="setup_key">
<input type="button" value="<?php echo func::tr('Select all'); ?>" onclick="document.getElementById('setup_key').select();" style="display: none" id="sel_all_btn"><br><br>
<input type="submit" value="<?php echo func::tr('Continue'); ?>" onclick="location.reload(); return false">
</form>
<a href="../index.php"><?php echo func::tr('Login page'); ?></a>
</td></tr></table>
<script>
document.getElementById('sel_all_btn').style.display='';
</script>
<?php
require ROOT.'include/page/page_foot_codes.php';
?>
</body>
</html>
