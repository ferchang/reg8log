<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
$parent_page=true;

$index_dir='../';

require_once $index_dir.'include/common.php';

require $index_dir.'include/code/code_encoding8anticache_headers.php';

if(!$debug_mode) exit('<center><h3>Error: Debug mode is off!</h3><a href="../index.php">Login page</a></center>');

if(isset($_COOKIE['reg8log_session'])) {
	require $index_dir.'include/code/sess/code_sess_start.php';
	
	if(isset($_POST['destroy'])) {
		require $index_dir.'include/code/sess/code_sess_destroy.php';
		header("Location: {$_SERVER['PHP_SELF']}");
		exit;
	}
	if(isset($session_contents_were_encrypted)) echo '<center style="background: green; color: #FFF; padding: 3px">Encrypted session</center>';
	else echo '<center style="background: #000; color: #FFF; padding: 3px">Unencrypted session</center>';
	echo '<br>';
	echo '<table align="center" cellpadding="10" style="border: thin solid #000"><tr><td><pre>';
	print_r($_SESSION);
	echo '</pre><br><center><form style="margin-bottom: 0px" method="post" action=""><input type="submit" name="destroy" value="Destroy session"></form></center></td></tr></table>';
}
else echo '<center>No session (cookie) exists.</center>';
echo '<br><center><a href="../index.php">Login page</a></center>';

?>