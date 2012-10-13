<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");



if($ch_pswd_max_threshold==-1 and $ch_pswd_captcha_threshold==-1) return;

if($try_type==='email') {
	if($ch_pswd_captcha_threshold==-1) return;
	require_once $index_dir.'include/code/code_db_object.php';
	$query="update `accounts` set `last_ch_email_try`=".time().' where `username`='.$reg8log_db->quote_smart($identified_user).' limit 1';
	$reg8log_db->query($query);
	return;
}

//--------------------------------

if(isset($_COOKIE['reg8log_ch_pswd_try'])) setcookie('reg8log_ch_pswd_try', $_COOKIE['reg8log_ch_pswd_try']+1, 0, '/', null, $https, true);
else setcookie('reg8log_ch_pswd_try', '1', 0, '/', null, $https, true);

//--------------------------------

if(!isset($trec)) {
	require_once $index_dir.'include/code/code_db_object.php';
	$query="select `last_ch_email_try`, `ch_pswd_tries`, `last_ch_pswd_try` from `accounts` where `username`=".$reg8log_db->quote_smart($identified_user).' limit 1';
	$reg8log_db->query($query);
	$trec=$reg8log_db->fetch_row();
}

//--------------------------------

if(time()-$trec['last_ch_pswd_try']>$lockdown_period) {
	$query='update `accounts` set `ch_pswd_tries`=1, `last_ch_pswd_try`='.time().' where `username`='.$reg8log_db->quote_smart($identified_user).' limit 1';
	$reg8log_db->query($query);
	if($ch_pswd_captcha_threshold!=-1 and $ch_pswd_captcha_threshold<=1) {
		$captcha_needed=true;
		$captcha_verified=false;
		if(isset($_SESSION['captcha_verified'])) unset($_SESSION['captcha_verified']);
	}
	if($ch_pswd_max_threshold!=-1 and $ch_pswd_max_threshold<=1) {
		require_once $index_dir.'include/func/func_random.php';
		$new_autologin_key=random_string(43);
		$query="update `accounts` set `autologin_key`='".$new_autologin_key."' where `username`=".$reg8log_db->quote_smart($identified_user).' limit 1';
		$reg8log_db->query($query);
		$query='update `accounts` set `ch_pswd_tries`=0, `last_ch_pswd_try`='.time().' where `username`='.$reg8log_db->quote_smart($identified_user).' limit 1';
		$reg8log_db->query($query);
		header("Location: {$index_dir}index.php");
		exit;
	}
	return;
}

//-----------------------------

$ch_pswd_tries=$trec['ch_pswd_tries']+1;
if($ch_pswd_tries>255) $ch_pswd_tries=255;

$query='update `accounts` set `ch_pswd_tries`='.$ch_pswd_tries.', `last_ch_pswd_try`='.time().' where `username`='.$reg8log_db->quote_smart($identified_user).' limit 1';
$reg8log_db->query($query);

if($ch_pswd_captcha_threshold!=-1 and $ch_pswd_captcha_threshold<=$ch_pswd_tries) {
	$captcha_needed=true;
	$captcha_verified=false;
	if(isset($_SESSION['captcha_verified'])) unset($_SESSION['captcha_verified']);
}

if($ch_pswd_max_threshold!=-1 and $ch_pswd_max_threshold<=$ch_pswd_tries) {
	require_once $index_dir.'include/func/func_random.php';
	$new_autologin_key=random_string(43);
	$query="update `accounts` set `autologin_key`='".$new_autologin_key."' where `username`=".$reg8log_db->quote_smart($identified_user).' limit 1';
	$reg8log_db->query($query);
	$query='update `accounts` set `ch_pswd_tries`=0, `last_ch_pswd_try`='.time().' where `username`='.$reg8log_db->quote_smart($identified_user).' limit 1';
	$reg8log_db->query($query);
	header("Location: {$index_dir}index.php");
	exit;
}

?>
