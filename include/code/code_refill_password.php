<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(strpos($refill, "hashed-$site_salt")===0) {
	
	$refill_output=' value="encrypted-'.$site_salt.'-'.base64_encode(func::encrypt($refill)).'" ';
}
else if(strpos($refill, "encrypted-$site_salt")===0) $refill_output=' value="'.$refill.'" ';
else if(config::get('password_refill')===2) {
	$refill='hashed-'.$site_salt.'-'.hash('sha256', $site_salt.$refill);
	
	$refill_output=' value="encrypted-'.$site_salt.'-'.base64_encode(func::encrypt($refill)).'" ';
}
else $refill_output='';

echo $refill_output;

?>
