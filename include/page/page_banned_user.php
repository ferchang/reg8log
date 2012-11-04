<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

?>

<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<META HTTP-EQUIV="EXPIRES" CONTENT="0">
<title>Banned account</title>
<script>
function onLogout() {
	exp = new Date();
	exp.setTime(exp.getTime()+(30*1000));
	cookie='reg8log_autologin2=logout;path=/';
	cookie+=';expires='+exp.toGMTString();
	<?php
	if($https) echo "cookie+=';secure';\n";
	?>
	document.cookie=cookie;
	return true;
}
</script>
<meta http-equiv="generator" content="Kate" />
<style>
h3 {
	padding: 0px;
	margin: 5px;
}
</style>
</head>
<body bgcolor="#D1D1E9" text="#000000" link="#0000FF" vlink="#800080" alink="#FF0000"><table width="100%" height="100%" style="border: 10px solid brown"><tr><td align="center">

<?php

echo '<h3 style="color: red">Your account has been banned by Admin!</h3>';
if($ban_reason!=='') echo '<h4>Ban reason: <span style="color: #84f;">', htmlspecialchars($ban_reason, ENT_QUOTES, 'UTF-8'), '</span></h4>';
if($ban_until!=1) {
	require_once $index_dir.'include/func/duration2friendly_str.php';
	echo '<h4>Ban will be lifted at:  <span style="color: #84f;">', duration2friendly_str($ban_until-$req_time, 2), '</span> later.</h4>';
}
else echo '<h4>Ban will be lifted at:  <span style="color: #84f;">Not specified.</span></h4>';
echo '<br>';

echo '<a onclick="return onLogout()" href="logout.php?antixsrf_token=', $_COOKIE['reg8log_antixsrf_token'], '">Log out</a>';

?>

</td></tr></table>
<?php
require $index_dir.'include/page/page_foot_codes.php';
?>
</body>
</html>
