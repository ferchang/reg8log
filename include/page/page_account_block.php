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
<title><?php echo func::tr('Account block'); ?></title>
<style>
</style>
<script language="javascript">
</script>
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
<?php

$tmp19=func::duration2friendly_str($block_duration, 0);
echo '<br><br><span style="font-size: 15pt; background: #fff; padding: 5px; border: thin solid #000">', sprintf(func::tr('account block msg'), htmlspecialchars($account_block, ENT_QUOTES, 'UTF-8'), $tmp19), '.</span>';
if(config::get('block_bypass_system_enabled')===3 or (config::get('block_bypass_system_enabled')===1 and strtolower($_POST['username'])==='admin') or (config::get('block_bypass_system_enabled')===2 and strtolower($_POST['username'])!=='admin')) {
?>
<br><br><span style="background: #fff; padding: 5px; border: thin solid #000;"><?php echo func::tr('If you are the owner of this account you can'); ?> <a href="block_bypass_request.php?username=<?php echo urlencode(htmlspecialchars($account_block, ENT_QUOTES, 'UTF-8')); ?>"><?php echo func::tr('request a block-bypass link be sent to your email'); ?>.</a></span>
<?php } ?>
</center>
<?php
require ROOT.'include/page/page_foot_codes.php';
?>
</body>
</html>
