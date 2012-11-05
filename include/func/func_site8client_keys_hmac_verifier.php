<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(!isset($hamc_verifier)) {
	if(!class_exists('Crypt_Hash')) require_once $index_dir.'include/class/class_aes_cipher.php';
	$hamc_verifier = new Crypt_Hash('sha1');
}

require_once $index_dir.'include/code/code_fetch_site_vars.php';

require_once $index_dir.'include/config/config_crypto.php';

$hamc_verifier->setKey(pack('H*', md5($pepper.$site_encr_key.$client_sess_key)));

function verify_hmac($ciphertext) {
	global $hamc_verifier;
	$hmac=substr($ciphertext, 0, 20);
	$ciphertext=substr($ciphertext, 20);
	if($hmac===$hamc_verifier->hash($ciphertext)) return true;
	else return false;
}

?>
