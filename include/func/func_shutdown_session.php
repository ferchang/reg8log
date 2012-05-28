<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");
$parent_page=true;

if(!isset($index_dir)) $index_dir='';

require_once $index_dir.'include/func/func_encryption_with_site8client_keys.php';

function shutdown_session() {

global $session_decryption_error;
global $index_dir;

if(session_id()==='' or isset($session_decryption_error) or session_name()!=='reg8log_session') return;

global $encrypt_session_files_contents;
global $session1;
global $session0;
global $old_use_cookies;
global $old_cookie_lifetime;
global $old_use_only_cookies;
global $old_gc_maxlifetime;
global $old_session_save_path;
global $old_session_name;
global $old_httponly;
global $old_trans_sid;
global $old_cookie_secure;
global $parent_page;
global $old_session_id;
global $client_sess_key;
global $https;

if(empty($_SESSION)) {
	@ setcookie('reg8log_session', false, mktime(12,0,0,1, 1, 1990), '/', null, $https, true);
	@ touch(session_save_path().'/sess_'.session_id(), 0, 0);
	@ session_destroy();
	@ unlink(session_save_path().'/sess_'.session_id());
	ini_set('session.use_cookies', $old_use_cookies);
	ini_set('session.use_only_cookies', $old_use_only_cookies);
	ini_set('session.gc_maxlifetime', $old_gc_maxlifetime);
	ini_set('session.cookie_httponly', $old_httponly);
	ini_set('session.use_trans_sid', $old_trans_sid);
	if($https) ini_set('session.cookie_secure', $old_cookie_secure);
	session_set_cookie_params($old_cookie_lifetime);
	session_save_path($old_session_save_path);
	session_name($old_session_name);
	session_id($old_session_id);
	return;
}

if($encrypt_session_files_contents) {
	if(isset($session0) and serialize($session1)===serialize($_SESSION)) $_SESSION=$session0;
	else {
		$session1=$_SESSION;
		$_SESSION=null;
		$_SESSION['reg8log_encrypted_session']=encrypt(serialize($session1));
	}
}

session_write_close();
ini_set('session.use_cookies', $old_use_cookies);
ini_set('session.use_only_cookies', $old_use_only_cookies);
ini_set('session.gc_maxlifetime', $old_gc_maxlifetime);
ini_set('session.use_trans_sid', $old_trans_sid);
if($https) ini_set('session.cookie_secure', $old_cookie_secure);
ini_set('session.cookie_httponly', $old_httponly);
session_set_cookie_params($old_cookie_lifetime);
session_save_path($old_session_save_path);
session_name($old_session_name);
session_id($old_session_id);

}

?>
