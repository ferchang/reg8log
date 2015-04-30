<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(!isset($GLOBALS['hamc_verifier'])) $GLOBALS['hamc_verifier'] = new Crypt_Hash('sha1');

$GLOBALS['hamc_verifier']->setKey(pack('H*', md5(config::get('pepper').SITE_ENCR_KEY.$GLOBALS['client_sess_key'])));

function verify_hmac($ciphertext) {
	$hmac=substr($ciphertext, 0, 20);
	$ciphertext=substr($ciphertext, 20);
	if($hmac===$GLOBALS['hamc_verifier']->hash($ciphertext)) return true;
	else return false;
}

?>
