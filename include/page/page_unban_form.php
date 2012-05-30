<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(!isset($index_dir)) $index_dir='';

$parent_page=true;

?>

<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<META HTTP-EQUIV="EXPIRES" CONTENT="0">
<script src="../js/common.js"></script>
<title>Unban user</title>
<style>
.unit {
	color: #8fd;
}
</style>
</head>
<body bgcolor="#D1D1E9" text="#000000" link="#0000FF" vlink="#800080" alink="#FF0000">
<table width="100%" height="100%"><tr><td align="center">
<form name="ban_form2" action="" method="post">
<table bgcolor="#7587b0" style="padding: 5px" >
<?php

if(!empty($err_msgs)) {
	echo '<tr align="center"><td colspan="3"  style="border: solid thin yellow; font-style: italic;"><span style="color: #800">Errors:</span><br />';
	foreach($err_msgs as $err_msg) {
		$err_msg[0]=strtoupper($err_msg[0]);
		echo "<span style=\"color: yellow\" >$err_msg</span><br />";
	}
	echo '</td></tr>';
}

echo '<input type="hidden" name="antixsrf_token" value="';
echo $_COOKIE['reg8log_antixsrf_token'];
echo '">';

require $index_dir.'include/code/code_generate_form_id.php';

?>

<tr align="center"><td>
<?php
echo '<input type="hidden" name="username" value="', htmlspecialchars($rec['username'], ENT_QUOTES, 'UTF-8'), '">';
if(!$rec['banned'] or ($rec['banned']!=1 and $rec['banned']<time())) echo '<h3>User <span style="color: yellow">', htmlspecialchars($rec['username'], ENT_QUOTES, 'UTF-8'), '</span> is not banned!</h3>';
else {
echo '<table border style="margin-top: 7px">
<tr style="background: brown; color: #fff"><th>Username</th><th>uid</th><th>Email</th><th>Gender</th><th>Member for</th></tr><tr style="background: #ccc" align="center">';
echo '<td>', htmlspecialchars($rec['username'], ENT_QUOTES, 'UTF-8'), '</td>';
echo '<td>', $rec['uid'], '</td>';
echo '<td>', $rec['email'], '</td>';
echo '<td>', $rec['gender'], '</td>';
require_once $index_dir.'include/func/func_duration2msg.php';
echo '<td>', duration2friendly_str(time()-$rec['timestamp']), '</td>';
echo '</tr></table><br></td></tr><tr><td align="left">';
if($ban_reason!=='') echo 'Ban reason: <span style="color: #8fd;">', htmlspecialchars($ban_reason, ENT_QUOTES, 'UTF-8'), '</span><br>';
if($ban_until!=1) {
	require_once $index_dir.'include/func/func_duration2msg.php';
	echo 'Ban until:  <span style="color: #8fd;">', duration2friendly_str($ban_until-time(), 2), '</span> later.';
}
else echo 'Ban until:  <span style="color: #8fd;">Not specified.</span>';
echo '<br><br></td></tr>';
}
echo '<tr><td align="center"><input type="submit" value="Cancel" name="cancel" />&nbsp;';
if($rec['banned']) echo '<input type="submit" value="Unban" name="unban_form" /></td>';
?>
</tr></table>
</form>
<a href="index.php">Admin operations</a><br><br>
<a href="../index.php">Login page</a>
</td></tr></table>
</body>
</html>
