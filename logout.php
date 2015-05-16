<?php

if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
define('CAN_INCLUDE', true);

require 'include/common.php';

require ROOT.'include/code/code_prevent_xsrf.php';

if(config::get('log_last_activity')) $flag=true;

config::set('log_last_activity', false);

$pass_banned_user=true;

if(config::get('change_autologin_key_upon_logout') or config::get('admin_change_autologin_key_upon_logout')) require_once ROOT.'include/code/code_identify.php';

if((isset($identified_user) and $identified_user==='Admin') or (isset($logged_out_user) and $logged_out_user==='Admin')) config::set('change_autologin_key_upon_logout', config::get('admin_change_autologin_key_upon_logout'));

if(config::get('change_autologin_key_upon_logout')) {
	if(isset($identified_user) or isset($banned_user) or isset($logged_out_user)) {
		if(isset($identified_user)) $tmp36=$GLOBALS['reg8log_db']->quote_smart($identified_user);
		else if(isset($banned_user)) $tmp36=$GLOBALS['reg8log_db']->quote_smart($banned_user);
		else $tmp36=$GLOBALS['reg8log_db']->quote_smart($logged_out_user);
		$new_autologin_key=func::random_string(43);
		$query="update `accounts` set `autologin_key`='$new_autologin_key'";
		if(isset($flag)) $query.=', `last_activity`='.REQUEST_TIME;
		if(config::get('log_last_logout')) $query.=', `last_logout`='.REQUEST_TIME;
		$query.=' where `username`='.$tmp36.' limit 1';
		$GLOBALS['reg8log_db']->query($query);
	}
}

if(config::get('log_last_logout') and !config::get('change_autologin_key_upon_logout')) {
	require_once ROOT.'include/code/code_identify.php';
	if(isset($identified_user) or isset($banned_user) or isset($logged_out_user)) {
		if(isset($identified_user)) $tmp36=$GLOBALS['reg8log_db']->quote_smart($identified_user);
		else if(isset($banned_user)) $tmp36=$GLOBALS['reg8log_db']->quote_smart($banned_user);
		else $tmp36=$GLOBALS['reg8log_db']->quote_smart($logged_out_user);
		$query='update `accounts` set `last_logout`='.REQUEST_TIME;
		if(isset($flag)) $query.=', `last_activity`='.REQUEST_TIME;
		$query.=' where `username`='.$tmp36.' limit 1';
		$GLOBALS['reg8log_db']->query($query);
	}
}

$user=new hm_user(config::get('identify_structs'));
if($user->logout()) header('Location: index.php');
else {
	$failure_msg=(config::get('debug_mode'))? $user->err_msg : func::tr('Problem logging out');
	require ROOT.'include/page/page_failure.php';
	exit;
}

?>
