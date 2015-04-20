<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
define('CAN_INCLUDE', true);

require_once '../include/common.php';

require ROOT.'include/code/admin/code_require_admin.php';

$error_log_file=ROOT.'file_store/error_log.txt';

if(isset($_POST['clear'])) {
	require ROOT.'include/code/code_prevent_xsrf.php';
	file_put_contents($error_log_file, '');
}

$logs=file_get_contents($error_log_file);

?>
<html>
<head>
<style>
body {
	color: #fff;
	background: #555;
}
textarea {
	display: block;
	width: 100%;
	height: 88%;
	margin-bottom: 10px;
	border: thin solid #000;
	background: #aaa;
	color: #000;
	padding: 5px;
	<?php if($logs==='') echo "text-align: center;\n"; ?>
}
a {
	background: #aaa;
	padding: 3px
}
</style>
<script>
function reload() {
	location.href=location.pathname+'?'+(new Date().getTime());
}
</script>
</head>
<body>
<textarea readonly>
<?php
if($logs==='') echo "\n\nError log file is empty.";
else echo $logs;
?>
</textarea>
<form action="" method="post">
<?php
echo '<input type="hidden" name="antixsrf_token" value="';
echo $_SESSION['reg8log']['antixsrf_token4post'];
echo '">';
?>
<center>
<a href="../index.php">Login page</a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type=submit value='Reload' onclick='reload(); return false;'>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type=submit name=clear value='Clear error log'>
</center>
</form>
</body>
</html>
