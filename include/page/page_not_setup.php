<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(!isset($index_dir)) $index_dir='';

?>

<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<META HTTP-EQUIV="EXPIRES" CONTENT="0">
<title>No database table</title>
</head>
<body bgcolor="#7587b0">
<table width="100%" height="100%"><tr><td align="center">
<h3>It seems that database tables are not installed.<br><small><a href="setup/db_setup.php" style="color: #fff">Go for installing database tables with the installer</a></small></h3>
</td></tr></table>
</body>
</html>
