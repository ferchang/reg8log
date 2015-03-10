<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(!isset($aes)) {
	require_once ROOT.'include/class/class_aes_cipher.php';
	$aes = new Crypt_AES();//default mode: CBC
}

require_once ROOT.'include/code/code_fetch_site_vars.php';

if(!isset($_COOKIE['reg8log_site_salt'])) setcookie('reg8log_site_salt', $site_salt, 0, '/', null, HTTPS, true);

require_once ROOT.'include/config/config_crypto.php';

$aes->setKey(pack('H*', md5($pepper.$site_encr_key.$client_sess_key)));

function encrypt($str) {
	global $aes;
	return $aes->IvEncryptHmac($str);
}

function decrypt($str) {
	global $aes;
	return $aes->IvDecryptHmac($str);
}

?>
