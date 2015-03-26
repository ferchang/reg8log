<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

function shutdown_session() {

if(empty($_SESSION)) return;

global $session_decryption_error;

if(session_id()==='' or isset($session_decryption_error) or session_name()!=='reg8log_session') return;

global $session1;
global $session0;

global $old_session_settings;
global $client_sess_key;

if(config::get('encrypt_session_files_contents')) {
	if(isset($session0) and serialize($session1)===serialize($_SESSION['reg8log']))  {
	$_SESSION['reg8log_encrypted_session']=$session0;
	unset($_SESSION['reg8log']);
	}
	else {
		$session1=$_SESSION['reg8log'];
		unset($_SESSION['reg8log']);
		$_SESSION['reg8log_encrypted_session']=func::encrypt(serialize($session1));
	}
}
else unset($_SESSION['reg8log_encrypted_session']);

session_write_close();

require ROOT.'include/code/sess/code_restore_old_sess_settings.php';

}

?>
