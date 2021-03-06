<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(session_id()!=='' and session_name()==='reg8log_session') return;

@session_write_close();

require ROOT.'include/code/sess/code_set_sess_settings.php';

if(!session_start()) {
	$failure_msg="session_start failed";
	require ROOT.'include/page/page_failure.php';
	exit;
}

session_regenerate_id(true);

if(!DB_INSTALLED) return;

if(isset($_SESSION['reg8log_encrypted_session'])) {
	if($encrypt_session_files_contents) $session0=$_SESSION['reg8log_encrypted_session'];
	require_once ROOT.'include/func/func_encryption_with_site8client_keys.php';
	$tmp5=unserialize(decrypt($_SESSION['reg8log_encrypted_session']));
	if($tmp5===false) {
		if(!defined('SETUP_PAGE')) {
			//setcookie('reg8log_session', false, mktime(12,0,0,1, 1, 1990), '/', null, HTTPS, true);
			unset($_SESSION['reg8log_encrypted_session']);
			$failure_msg="<h3 dir=ltr>Session decryption error!</h3>";
			$no_specialchars=true;
			require ROOT.'include/page/page_failure.php';
			$session_decryption_error=true;
			exit;
		}
	} else $_SESSION['reg8log']=$tmp5;
}
else if($encrypt_session_files_contents and !empty($_SESSION['reg8log'])) {
	$_SESSION['reg8log']=null;
	echo '<span dir=ltr>Warning: Unecrypted session contents! <small>(Session contents cleared)</small></span><br>';
}

if($encrypt_session_files_contents) if(isset($_SESSION['reg8log'])) $session1=$_SESSION['reg8log'];
else $session1=null;

//unset($GLOBALS['session0'], $GLOBALS['session1']) in class_config_loader.php

?>