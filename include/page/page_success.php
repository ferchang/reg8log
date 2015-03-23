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

      <title><?php echo func::tr('Operation successful'); ?></title>
      <script language="javascript">

</script>
</head>

<body bgcolor="#D1D1E9" text="#000000" link="#0000FF" vlink="#800080" alink="#FF0000" <?php echo $page_dir; ?>><table width="100%" height="100%" style="border: 10px solid #080"><tr><td align="center">

<?php

if(!isset($no_specialchars)) {
	echo '<h3>', htmlspecialchars($success_msg, ENT_QUOTES, 'UTF-8'), '</h3>';
	if(isset($additional_link)) echo "<a href=\"{$additional_link[1]}\">{$additional_link[0]}</a><br>";
	echo "<a href=\"{$index_dir}index.php\">", func::tr('Login page'), "</a>";
}
else {
	echo $success_msg;
	if(isset($additional_link)) echo "<a href=\"{$additional_link[1]}\">{$additional_link[0]}</a><br>";
	echo "<br /><a href=\"{$index_dir}index.php\">", func::tr('Login page'), "</a>";
}

?>

</td></tr></table>
<?php
require ROOT.'include/page/page_foot_codes.php';
?>
</body>
</html>
