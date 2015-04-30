<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(strpos($refill, 'hashed-'.SITE_SALT)===0) {
	
	$refill_output=' value="encrypted-'.SITE_SALT.'-'.base64_encode(func::encrypt($refill)).'" ';
}
else if(strpos($refill, 'encrypted-'.SITE_SALT)===0) $refill_output=' value="'.$refill.'" ';
else if(config::get('password_refill')===2) {
	$refill='hashed-'.SITE_SALT.'-'.hash('sha256', SITE_SALT.$refill);
	
	$refill_output=' value="encrypted-'.SITE_SALT.'-'.base64_encode(func::encrypt($refill)).'" ';
}
else $refill_output='';

echo $refill_output;

?>
