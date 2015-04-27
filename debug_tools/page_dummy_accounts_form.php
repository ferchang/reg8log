<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

?>

<html>

<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<META HTTP-EQUIV="EXPIRES" CONTENT="0">
<title>Dummy accounts</title>
<style>
	button {
		margin-left: 1; margin-right: 1
	}
</style>
</head>

<body bgcolor="#e1cfa0" text="#000000" link="#0000FF" vlink="#800080" alink="#FF0000" style="margin: 0;">

<table width="100%"  height="95%" cellpadding="5" cellspacing="0">
<tr valign="middle" align=center><td>
Dummy accounts are used for some few testing purposes like testing pagination.
<br>
They are just random gibberish and not login-able. Don't forget to delete them when you are done!
<br><br>
<table style='border: thin solid #000; padding: 5px;' bgcolor="#7587b0"><tr><td>
<?php
if(isset($msg)) echo "<div align=center style='background: orange; padding: 5px; color: #000'>$msg</div><br>";
?>
<form action="" method="post">
<center><b>Create some dummy accounts</b></center><br>
Number of accounts: <input type=text name=num size=5 style='text-align: center'><br>
Pending for email verification: <input type=checkbox name=pending4email><br>
Pending for admin confirmation: <input type=checkbox name=pending4admin><br>
<?php
echo '<input type="hidden" name="antixsrf_token" value="';
echo ANTIXSRF_TOKEN4POST;
echo '">';
?>
<br><center><input type=submit value='Create'></center>
</td></tr>
</table>
<br>
<?php
if(isset($del_msg)) echo "<span align=center style='background: blue; padding: 5px; color: #000'>$del_msg</span><br>";
?>
<br><input type=submit name=delete_all value='Delete all dummy accounts'>
</form>
<br><br><center><a href="../index.php">Login page</a></center>
</td></tr>
</table>
</body>
</html>
