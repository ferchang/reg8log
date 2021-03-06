<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$index_dir=func::get_relative_root_path();

if(config::get('ch_pswd_max_threshold')===-1 and config::get('ch_pswd_captcha_threshold')===-1) return;

if($try_type==='email') {
	if(config::get('ch_pswd_captcha_threshold')===-1) return;
	$query="update `accounts` set `last_ch_email_try`=".REQUEST_TIME.' where `username`='.$GLOBALS['reg8log_db']->quote_smart($identified_user).' limit 1';
	$GLOBALS['reg8log_db']->query($query);
	return;
}

//--------------------------------

if(isset($_COOKIE['reg8log_ch_pswd_try'])) setcookie('reg8log_ch_pswd_try', $_COOKIE['reg8log_ch_pswd_try']+1, 0, '/', null, HTTPS, true);
else setcookie('reg8log_ch_pswd_try', '1', 0, '/', null, HTTPS, true);

//--------------------------------

if(!isset($trec)) {
	$query="select `last_ch_email_try`, `ch_pswd_tries`, `last_ch_pswd_try` from `accounts` where `username`=".$GLOBALS['reg8log_db']->quote_smart($identified_user).' limit 1';
	$GLOBALS['reg8log_db']->query($query);
	$trec=$GLOBALS['reg8log_db']->fetch_row();
}

//--------------------------------

if(REQUEST_TIME-$trec['last_ch_pswd_try']>config::get('account_block_period')) {
	$query='update `accounts` set `ch_pswd_tries`=1, `last_ch_pswd_try`='.REQUEST_TIME.' where `username`='.$GLOBALS['reg8log_db']->quote_smart($identified_user).' limit 1';
	$GLOBALS['reg8log_db']->query($query);
	if(config::get('ch_pswd_captcha_threshold')!==-1 and config::get('ch_pswd_captcha_threshold')<=1) {
		$captcha_needed=true;
		$captcha_verified=false;
		if(isset($_SESSION['reg8log']['captcha_verified'])) unset($_SESSION['reg8log']['captcha_verified']);
	}
	if(config::get('ch_pswd_max_threshold')!==-1 and config::get('ch_pswd_max_threshold')<=1) {
		
		$new_autologin_key=func::random_string(43);
		$query="update `accounts` set `autologin_key`='".$new_autologin_key."' where `username`=".$GLOBALS['reg8log_db']->quote_smart($identified_user).' limit 1';
		$GLOBALS['reg8log_db']->query($query);
		$query='update `accounts` set `ch_pswd_tries`=0, `last_ch_pswd_try`='.REQUEST_TIME.' where `username`='.$GLOBALS['reg8log_db']->quote_smart($identified_user).' limit 1';
		$GLOBALS['reg8log_db']->query($query);
		setcookie('reg8log_ch_pswd_try', false, mktime(12,0,0,1, 1, 1990), '/', null, HTTPS, true);
		header("Location: {$index_dir}index.php");
		exit;
	}
	return;
}

//-----------------------------

$ch_pswd_tries=$trec['ch_pswd_tries']+1;
if($ch_pswd_tries>255) $ch_pswd_tries=255;

$query='update `accounts` set `ch_pswd_tries`='.$ch_pswd_tries.', `last_ch_pswd_try`='.REQUEST_TIME.' where `username`='.$GLOBALS['reg8log_db']->quote_smart($identified_user).' limit 1';
$GLOBALS['reg8log_db']->query($query);

if(config::get('ch_pswd_captcha_threshold')!==-1 and config::get('ch_pswd_captcha_threshold')<=$ch_pswd_tries) {
	$captcha_needed=true;
	$captcha_verified=false;
	if(isset($_SESSION['reg8log']['captcha_verified'])) unset($_SESSION['reg8log']['captcha_verified']);
}

if(config::get('ch_pswd_max_threshold')!==-1 and config::get('ch_pswd_max_threshold')<=$ch_pswd_tries) {
	
	$new_autologin_key=func::random_string(43);
	$query="update `accounts` set `autologin_key`='".$new_autologin_key."' where `username`=".$GLOBALS['reg8log_db']->quote_smart($identified_user).' limit 1';
	$GLOBALS['reg8log_db']->query($query);
	$query='update `accounts` set `ch_pswd_tries`=0, `last_ch_pswd_try`='.REQUEST_TIME.' where `username`='.$GLOBALS['reg8log_db']->quote_smart($identified_user).' limit 1';
	$GLOBALS['reg8log_db']->query($query);
	setcookie('reg8log_ch_pswd_try', false, mktime(12,0,0,1, 1, 1990), '/', null, HTTPS, true);
	header("Location: {$index_dir}index.php");
	exit;
}

?>
