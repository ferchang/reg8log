<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
define('CAN_INCLUDE', true);

define('SETUP_PAGE', true);

require '../include/common.php';

error_reporting(E_ALL);
ini_set('display_errors', '1');

$site_encr_key='ff'; //just to prevent func_encryption_with_site8client_keys.php from complaining!

require_once ROOT.'include/common.php';

require ROOT.'include/code/code_encoding8anticache_headers.php';

require ROOT.'include/code/code_prevent_repost.php';

$encrypt_session_files_contents=false;

require ROOT.'include/code/sess/code_sess_start.php';

require_once ROOT.'include/func/func_random.php';

$file_contents=file_get_contents('setup.txt');

if(empty($_SESSION['reg8log']['setup_key']) or strpos($file_contents, $_SESSION['reg8log']['setup_key'])===false) {
	$setup_key=random_string(22);
	$_SESSION['reg8log']['setup_key']=$setup_key;
	require ROOT.'setup/include/page_setup_form1.php';
	exit;
}

require ROOT.'include/config/config_register_fields.php';

require ROOT.'include/code/code_fetch_site_vars.php';

if(!isset($site_salt)) if(isset($_COOKIE['reg8log_site_salt'])) $site_salt=$_COOKIE['reg8log_site_salt'];
else {
	$site_salt=random_string(22);
	setcookie('reg8log_site_salt', $site_salt, 0, '/', null, HTTPS, true);
}

do {
if(!isset($_POST['username'])) break;

require ROOT.'include/code/code_prevent_xsrf.php';

require ROOT.'setup/include/code_validate_admin_register_submit.php';

if(strpos($_POST['password'], "hashed-$site_salt")!==0) $_POST['password']='hashed-'.$site_salt.'-'.hash('sha256', $site_salt.$_POST['password']);

if(isset($err_msgs)) break;

echo '<html ', $page_dir, '><head><meta http-equiv="Content-type" content="text/html;charset=UTF-8" /><META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE"><META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE"><META HTTP-EQUIV="EXPIRES" CONTENT="0"><title>', func::tr('DB setup - Final'), '</title></head><body ', $page_dir, ' bgcolor="#D1D1E9" text="#000000" link="#0000FF" vlink="#800080" alink="#FF0000"><table align="center" valign="center" height="100%"><tr><td><h4>';

require ROOT.'setup/include/code_create_tables.php';

echo '<hr style="width: 250px">';
require ROOT.'setup/include/code_create_site_vars.php';

echo '<hr style="width: 250px">';
require ROOT.'setup/include/code_add_admin_account.php';

echo func::tr('Account <span style="color: green">Admin</span> created'), '.<br>';

$query="insert into `admin_block_alerts` (`for`, `new_account_blocks`, `new_ip_blocks`) values ('visit', 0, 0)";
$reg8log_db->query($query);

$query="insert into `admin_block_alerts` (`for`, `new_account_blocks`, `new_ip_blocks`) values ('email', 0, 0)";
$reg8log_db->query($query);

$query="insert into `admin_registeration_alerts` (`for`, `new_registerations`) values ('visit', 0)";
$reg8log_db->query($query);

$query="insert into `admin_registeration_alerts` (`for`, `new_registerations`) values ('email', 0)";
$reg8log_db->query($query);

$query="insert into `admin` (`last_password_check`, `password_check_key`) values (0, '')";
$reg8log_db->query($query);

$query="insert ignore into `dummy` (`i`) values (1)";
$reg8log_db->query($query);

require ROOT.'setup/include/check_file_permissions.php';

echo '</h4><center><h3>', func::tr('Setup completed'), '.</h3>';
echo '<a href="../index.php">', func::tr('Login page'), '</a></center>';

require ROOT.'include/code/code_set_submitted_forms_cookie.php';

echo '</td></tr></table>';
require ROOT.'include/page/page_foot_codes.php';
echo '</body></html>';

unset($_SESSION['reg8log']['setup_key']);
require ROOT.'include/code/sess/code_sess_destroy.php';

exit;
} while(false);

require ROOT.'setup/include/page_setup_form2.php';

?>
