<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(config::get('ch_pswd_captcha_threshold')===-1) return;
if(config::get('ch_pswd_captcha_threshold')===0) {
	$captcha_needed=true;
	return;
}

$query="select `last_ch_email_try`, `ch_pswd_tries`, `last_ch_pswd_try` from `accounts` where `username`=".$GLOBALS['reg8log_db']->quote_smart($identified_user).' limit 1';

$GLOBALS['reg8log_db']->query($query);

$trec=$GLOBALS['reg8log_db']->fetch_row();

if($try_type==='email') {
	if($trec['last_ch_email_try']+12*60*60>REQUEST_TIME) $captcha_needed=true;
	return;
}

if($trec['ch_pswd_tries']>=config::get('ch_pswd_captcha_threshold') and REQUEST_TIME-$trec['last_ch_pswd_try']<config::get('ch_pswd_period')) $captcha_needed=true;

?>
