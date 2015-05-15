<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
define('CAN_INCLUDE', true);

require_once '../include/common.php';

if(!config::get('debug_mode')) {
	if(config::get('admin_error_log_access')) require ROOT.'include/code/admin/code_require_admin.php';
	else exit('Access to error log is not enabled in config file!');
}

if(!is_writable(ERROR_LOG_FILE)) $not_writable='Error log file not writable!';

if(isset($_POST['clear'])) {
	if(!config::get('debug_mode') and config::get('admin_error_log_access')!==2) exit('Clear access to error log is not enabled in config file!');
	require ROOT.'include/code/code_prevent_xsrf.php';
	if(isset($not_writable)) $not_writable='Cannot clear error log file! Error log file not writable.';
	else file_put_contents(ERROR_LOG_FILE, ERROR_LOG_HEADER_STR, LOCK_EX);
}

if(!is_readable(ERROR_LOG_FILE)) $not_readable=true;
else {
	$logs=file_get_contents(ERROR_LOG_FILE);
	if(substr($logs, 0, strlen(ERROR_LOG_HEADER_STR))===ERROR_LOG_HEADER_STR) $logs=substr($logs, strlen(ERROR_LOG_HEADER_STR));
	if($logs===false) $logs='';
}//same code in admin/index.php

if(isset($not_readable)) $current_hash='?';
else $current_hash=substr(hash('sha256', $logs), 0, 32);

//-------------------------

if(!isset($not_readable) and $logs!=='') {
	$query="select last_hash from error_log_hash limit 1";
	$reg8log_db->query($query);
	$rec=$reg8log_db->fetch_row();
	if($current_hash!==$rec['last_hash']) $new=true;
}

//-------------------------

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
	background: #aaa;
	<?php
	if(isset($new)) echo "border: medium solid #f00;\n";
	else echo "border: thin solid #000;\n";
	if(isset($not_readable)) echo "color: red;\n";
	else echo "color: #000;\n";
	if(isset($not_readable) or $logs==='') echo "text-align: center;\n"; 
	?>
	padding: 5px;
	
}
a {
	background: #aaa;
	padding: 3px
}
#error {
	color: red;
	background: #000;
	padding: 3px;
	display: inline;
}
</style>
<script>
function reload() {
	target=location.pathname+'?';
	if(location.search.indexOf('admin_ops')!=-1) target+='admin_ops&';
	target+=(new Date().getTime());
	location.href=target;
}
</script>
</head>
<body>
<?php
if(isset($not_writable)) echo "<center><h4 align=center id=error>$not_writable</h4></center>";
?>
<textarea readonly>
<?php
if(isset($not_readable)) echo "\n\nError log file not readable!";
else if($logs==='') echo "\n\nError log file is empty.";
else echo $logs;
?>
</textarea>
<form action="" method="post">
<?php
echo '<input type="hidden" name="antixsrf_token" value="';
echo ANTIXSRF_TOKEN4POST;
echo '">';
?>
<center>
<?php
if(!config::get('log_errors')) echo '<span style="color: yellow;">Error logging is not enabled.</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
if(isset($_GET['admin_ops'])) echo '<a href="../admin/index.php">', func::tr('Admin operations'), '</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
?>
<a href="../index.php">Login page</a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type=submit value='Reload' onclick='reload(); return false;'>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type=submit name=clear value='Clear error log' <?php if(!config::get('debug_mode') and config::get('admin_error_log_access')!==2) echo 'disabled'; ?>>
</center>
</form>
</body>
</html>
<?php

$query="update error_log_hash set last_hash='$current_hash' limit 1";

$reg8log_db->query($query);

?>