<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(!isset($index_dir)) $index_dir='';

if(!isset($aes)) {
	require_once $index_dir.'include/class/class_aes_cipher.php';
	$aes = new Crypt_AES();//default mode: CBC
}

require_once $index_dir.'include/code/code_fetch_site_vars.php';

require_once $index_dir.'include/info/info_crypto.php';

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
