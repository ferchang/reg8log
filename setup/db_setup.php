<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
$parent_page=true;

$setup_page=true;

$index_dir='../';

$site_encr_key='ff'; //just to prevent func_encryption_with_site8client_keys.php from complaining!

require_once $index_dir.'include/common.php';

require $index_dir.'include/code/code_encoding8anticache_headers.php';

ob_start();

require $index_dir.'include/code/code_prevent_repost.php';

$encrypt_session_files_contents=false;

require $index_dir.'include/code/sess/code_sess_start.php';

require_once $index_dir.'include/func/func_random.php';

$file_contents=file_get_contents('setup.txt');

if(empty($_SESSION['setup_key']) or strpos($file_contents, $_SESSION['setup_key'])===false) {
	$setup_key=random_string(22);
	$_SESSION['setup_key']=$setup_key;
	require $index_dir.'setup/include/page_setup_form1.php';
	exit;
}

require $index_dir.'include/config/config_register_fields.php';

require $index_dir.'include/code/code_fetch_site_vars.php';

require $index_dir.'include/code/code_set_site_salt.php';

do {
if(!isset($_POST['username'])) break;

require $index_dir.'include/code/code_prevent_xsrf.php';

require $index_dir.'setup/include/code_validate_admin_register_submit.php';

if(strpos($_POST['password'], "hashed-$site_salt")!==0) $_POST['password']='hashed-'.$site_salt.'-'.hash('sha256', $site_salt.$_POST['password']);

if(isset($err_msgs)) break;

echo '<html ', $page_dir, '><head><meta http-equiv="Content-type" content="text/html;charset=UTF-8" /><META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE"><META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE"><META HTTP-EQUIV="EXPIRES" CONTENT="0"><title>', tr('DB setup - Final'), '</title></head><body ', $page_dir, ' bgcolor="#D1D1E9" text="#000000" link="#0000FF" vlink="#800080" alink="#FF0000"><table align="center" valign="center" height="100%"><tr><td><h4>';

require $index_dir.'setup/include/code_create_tables.php';

echo '<hr style="width: 250px">';
require $index_dir.'setup/include/code_create_site_vars.php';

echo '<hr style="width: 250px">';
require $index_dir.'setup/include/code_add_admin_account.php';

echo tr('Account <span style="color: green">Admin</span> created'), '.<br>';

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

echo '</h4><center><h3>', tr('Setup completed'), '.</h3>';
echo '<a href="../index.php">', tr('Login page'), '</a></center>';

require $index_dir.'include/code/code_set_submitted_forms_cookie.php';

echo '</td></tr></table>';
require $index_dir.'include/page/page_foot_codes.php';
echo '</body></html>';

unset($_SESSION['setup_key']);
require $index_dir.'include/code/sess/code_sess_destroy.php';
exit;
} while(false);

require $index_dir.'setup/include/page_setup_form2.php';

?>
