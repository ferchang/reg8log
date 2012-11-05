<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(session_id()!=='' and session_name()==='reg8log_session') return;

require $index_dir.'include/config/config_identify.php';

@session_write_close();

$old_cookie_lifetime=session_get_cookie_params();
$old_cookie_lifetime=$old_cookie_lifetime['lifetime'];
session_set_cookie_params(0);
$old_use_cookies=ini_set('session.use_cookies', '1');
$old_use_only_cookies=ini_set('session.use_only_cookies', '1');
$old_gc_maxlifetime=ini_set('session.gc_maxlifetime', $identify_structs['session']['gc_maxlifetime']);
$old_session_save_path=session_save_path($identify_structs['session']['save_path']);
$old_session_name=session_name('reg8log_session');
$old_session_id=session_id();
$old_httponly=ini_set("session.cookie_httponly", 1);
$old_trans_sid=ini_set("session.use_trans_sid", 0);
if($https) $old_cookie_secure=ini_set('session.cookie_secure', 'on');

if(!session_start()) {
	$failure_msg="session_start failed";
	require $index_dir.'include/page/page_failure.php';
	exit;
}

session_regenerate_id(true);

if(!isset($encrypt_session_files_contents)) require $index_dir.'include/config/config_crypto.php';

if(isset($_SESSION['reg8log_encrypted_session'])) {
	$session_contents_were_encrypted=true;
	if($encrypt_session_files_contents) $session0=$_SESSION;
	require_once $index_dir.'include/func/func_encryption_with_site8client_keys.php';
	$tmp5=unserialize(decrypt($_SESSION['reg8log_encrypted_session']));
	if($tmp5===false) {
		if(!isset($setup_page)) {
			setcookie('reg8log_session', false, mktime(12,0,0,1, 1, 1990), '/', null, $https, true);
			$failure_msg="Session decryption error!";
			require $index_dir.'include/page/page_failure.php';
			$session_decryption_error=true;
			exit;
		}
	} else $_SESSION=$tmp5;
}
else if($encrypt_session_files_contents and !empty($_SESSION)) {
	$_SESSION=null;
	echo 'Warning: Unecrypted session contents! <small>(Session contents cleared)</small><br>';
}

if($encrypt_session_files_contents) $session1=$_SESSION;

?>