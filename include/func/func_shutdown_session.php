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


if(empty($_SESSION)) {
	setcookie('reg8log_session', false, mktime(12,0,0,1, 1, 1990), '/', null, HTTPS, true);
	session_destroy();
	require ROOT.'include/code/sess/code_restore_old_sess_settings.php';
	return;
}

if(!isset($encrypt_session_files_contents)) require ROOT.'include/config/config_crypto.php';

if($encrypt_session_files_contents) {
	if(isset($session0) and serialize($session1)===serialize($_SESSION['reg8log']))  {
	$_SESSION['reg8log_encrypted_session']=$session0;
	unset($_SESSION['reg8log']);
	}
	else {
		$session1=$_SESSION['reg8log'];
		unset($_SESSION['reg8log']);
		$_SESSION['reg8log_encrypted_session']=encrypt(serialize($session1));
	}
}
else unset($_SESSION['reg8log_encrypted_session']);

session_write_close();

require ROOT.'include/code/sess/code_restore_old_sess_settings.php';

}

?>
