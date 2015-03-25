<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

require_once ROOT.'include/config/config_brute_force_protection.php';

if(config::get('ch_pswd_captcha_threshold')==-1) return;
if(config::get('ch_pswd_captcha_threshold')==0) {
	$captcha_needed=true;
	return;
}

require_once ROOT.'include/code/code_db_object.php';

$query="select `last_ch_email_try`, `ch_pswd_tries`, `last_ch_pswd_try` from `accounts` where `username`=".$reg8log_db->quote_smart($identified_user).' limit 1';

$reg8log_db->query($query);

$trec=$reg8log_db->fetch_row();

if($try_type==='email') {
	if($trec['last_ch_email_try']+12*60*60>$req_time) $captcha_needed=true;
	return;
}

if($trec['ch_pswd_tries']>=config::get('ch_pswd_captcha_threshold') and $req_time-$trec['last_ch_pswd_try']<config::get('ch_pswd_period')) $captcha_needed=true;

?>
