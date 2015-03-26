<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

function shutdown_session() {

if(empty($_SESSION)) return;

if(session_id()==='' or isset($GLOBALS['session_decryption_error']) or session_name()!=='reg8log_session') return;

if(config::get('encrypt_session_files_contents')) {
	if(isset($GLOBALS['session0']) and serialize($GLOBALS['session1'])===serialize($_SESSION['reg8log']))  {
	$_SESSION['reg8log_encrypted_session']=$GLOBALS['session0'];
	unset($_SESSION['reg8log']);
	}
	else {
		$GLOBALS['session1']=$_SESSION['reg8log'];
		unset($_SESSION['reg8log']);
		$_SESSION['reg8log_encrypted_session']=func::encrypt(serialize($GLOBALS['session1']));
	}
}
else unset($_SESSION['reg8log_encrypted_session']);

session_write_close();

global $old_session_settings;
require ROOT.'include/code/sess/code_restore_old_sess_settings.php';

}

?>
