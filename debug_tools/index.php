<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
define('CAN_INCLUDE', true);

?>

<html>

<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<META HTTP-EQUIV="EXPIRES" CONTENT="0">
<title>Debug tools</title>
</head>
<body bgcolor="#e1cfa0" text="#000000" link="#0000FF" vlink="#800080" alink="#FF0000" style="margin: 0;"><table width="100%"  height="95%" cellpadding="0" cellspacing="0">
<tr valign="middle" align=center><td>
<table style='border: thin solid #000; padding: 5px;' bgcolor="#e1cfd0"><tr><td>
<h3 align=center>Debug tools:</h3>
<big>
<a href="session.php">reg8log session</a><br><br>
<a href="cookies.php">reg8log cookies</a><br><br>
<a href="error_log.php">reg8log error log</a><br><br>
<a href="dummy_accounts.php">Dummy accounts</a>
</big>
</td></tr>
</table>
<br><center><a href="../index.php">Login page</a></center>
</td></tr>
</table>
</body>
</html>
