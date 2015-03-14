<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

require_once ROOT.'include/func/func_encryption_with_site8client_keys.php';

function shutdown_session() {

global $session_decryption_error;


if(session_id()==='' or isset($session_decryption_error) or session_name()!=='reg8log_session') return;

global $encrypt_session_files_contents;
global $session1;
global $session0;

global $old_session_settings;
global $client_sess_key;


if(empty($_SESSION) and 0) {
	@ setcookie('reg8log_session', false, mktime(12,0,0,1, 1, 1990), '/', null, HTTPS, true);
	@ touch(session_save_path().'/sess_'.session_id(), 0, 0);
	@ session_destroy();
	@ unlink(session_save_path().'/sess_'.session_id());
	ini_set('session.use_cookies', $old_session_settings['use_cookies']);
	ini_set('session.use_only_cookies', $old_session_settings['use_only_cookies']);
	ini_set('session.gc_maxlifetime', $old_session_settings['gc_maxlifetime']);
	ini_set('session.cookie_httponly', $old_session_settings['httponly']);
	ini_set('session.use_trans_sid', $old_session_settings['trans_sid']);
	if(HTTPS) ini_set('session.cookie_secure', $old_session_settings['cookie_secure']);
	session_set_cookie_params($old_session_settings['cookie_lifetime']);
	session_save_path($old_session_settings['session_save_path']);
	session_name($old_session_settings['session_name']);
	session_id($old_session_settings['session_id']);
	return;
}

if(!isset($encrypt_session_files_contents)) require ROOT.'include/config/config_crypto.php';

if($encrypt_session_files_contents) {
	if(isset($session0) and serialize($session1)===serialize($_SESSION)) $_SESSION=$session0;
	else {
		$session1=$_SESSION;
		$_SESSION=null;
		$_SESSION['reg8log_encrypted_session']=encrypt(serialize($session1));
	}
}

session_write_close();
ini_set('session.use_cookies', $old_session_settings['use_cookies']);
ini_set('session.use_only_cookies', $old_session_settings['use_only_cookies']);
ini_set('session.gc_maxlifetime', $old_session_settings['gc_maxlifetime']);
ini_set('session.use_trans_sid', $old_session_settings['trans_sid']);
if(HTTPS) ini_set('session.cookie_secure', $old_session_settings['cookie_secure']);
ini_set('session.cookie_httponly', $old_session_settings['httponly']);
session_set_cookie_params($old_session_settings['cookie_lifetime']);
session_save_path($old_session_settings['session_save_path']);
session_name($old_session_settings['session_name']);
session_id($old_session_settings['session_id']);

}

?>
