<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(config::get('email_verification_needed')) {
	$expired=REQUEST_TIME-config::get('email_verification_time');
	$query="delete from `pending_accounts` where `email_verification_key`!='' and `email_verified`=0 and `timestamp` < $expired";
	$GLOBALS['reg8log_db']->query($query);
}
else if(mt_rand(1, 10)===1) {
	$expired=REQUEST_TIME-config::get('email_verification_time');
	$query="delete from `pending_accounts` where `email_verification_key`!='' and `email_verified`=0 and `timestamp` < $expired";
	$GLOBALS['reg8log_db']->query($query);
}

if(config::get('admin_confirmation_needed')) {
	$expired=REQUEST_TIME-config::get('admin_confirmation_time');
	$query="delete from `pending_accounts` where `admin_confirmed`=0 and `timestamp` < $expired";
	$GLOBALS['reg8log_db']->query($query);
}
else if(mt_rand(1, 10)===1) {
	$expired=REQUEST_TIME-config::get('admin_confirmation_time');
	$query="delete from `pending_accounts` where `admin_confirmed`=0 and `timestamp` < $expired";
	$GLOBALS['reg8log_db']->query($query);
}

?>