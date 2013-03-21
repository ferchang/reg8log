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
<title><?php echo tr('Banned account'); ?></title>
<script src="js/logout.js"></script>
<style>
h3 {
	padding: 0px;
	margin: 5px;
}
</style>
</head>
<body bgcolor="#D1D1E9" text="#000000" link="#0000FF" vlink="#800080" alink="#FF0000" <?php echo $page_dir; ?>><table width="100%" height="100%" style="border: 10px solid brown"><tr><td align="center">

<?php

echo '<h3 style="color: red">', tr('Your account has been banned by Admin!'), '</h3>';
if($ban_reason!=='') echo '<h4>', tr('Ban reason'), ': <span style="color: #84f;">', htmlspecialchars($ban_reason, ENT_QUOTES, 'UTF-8'), '</span></h4>';
if($ban_until!=1) {
	require_once $index_dir.'include/func/func_duration2friendly_str.php';
	echo '<h4>', tr('Ban will be lifted at'),':  <span style="color: #84f;">', duration2friendly_str($ban_until-$req_time, 2), '</span>', tr(' later'), '.</h4>';
}
else echo '<h4>', tr('Ban will be lifted at'), ':  <span style="color: #84f;">', tr('Unlimited'), '.</span></h4>';
echo '<br>';

echo '<a onclick="return onLogout()" href="logout.php?antixsrf_token=', $_COOKIE['reg8log_antixsrf_token4get'], '">', tr('Log out'), '</a>';

?>

</td></tr></table>
<?php
require $index_dir.'include/page/page_foot_codes.php';
?>
</body>
</html>
