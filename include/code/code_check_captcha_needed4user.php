<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

require_once $index_dir.'include/info/info_lockdown.php';

if($ch_pswd_captcha_threshold==-1) return;
if($ch_pswd_captcha_threshold==0) {
	$captcha_needed=true;
	return;
}

require_once $index_dir.'include/code/code_db_object.php';

$query="select `last_ch_email_try`, `ch_pswd_tries`, `last_ch_pswd_try` from `accounts` where `username`=".$reg8log_db->quote_smart($identified_user).' limit 1';

$reg8log_db->query($query);

$trec=$reg8log_db->fetch_row();

if($try_type==='email') {
	if($trec['last_ch_email_try']+12*60*60>time()) $captcha_needed=true;
	return;
}

if($trec['ch_pswd_tries']>=$ch_pswd_captcha_threshold and time()-$trec['last_ch_pswd_try']<$ch_pswd_period) $captcha_needed=true;

?>
