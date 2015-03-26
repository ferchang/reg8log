<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(!isset($hamc_verifier)) $hamc_verifier = new Crypt_Hash('sha1');

require_once ROOT.'include/code/code_fetch_site_vars.php';

$hamc_verifier->setKey(pack('H*', md5(config::get('pepper').$GLOBALS['site_encr_key'].$GLOBALS['client_sess_key'])));

function verify_hmac($ciphertext) {
	global $hamc_verifier;
	$hmac=substr($ciphertext, 0, 20);
	$ciphertext=substr($ciphertext, 20);
	if($hmac===$hamc_verifier->hash($ciphertext)) return true;
	else return false;
}

?>
