<?php

if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
$parent_page=true;

$index_dir='./';

require $index_dir.'include/common.php';

require $index_dir.'include/code/code_prevent_xsrf.php';

require_once $index_dir.'include/class/class_cookie.php';
require_once $index_dir.'include/class/class_user.php';
require_once $index_dir.'include/config/config_identify.php';

if($log_last_activity) $flag=true;

$log_last_activity=false;

$pass_banned_user=true;

if($change_autologin_key_upon_logout) {
	require_once $index_dir.'include/code/code_identify.php';
	if(isset($identified_user) or isset($banned_user) or isset($logged_out_user)) {
		if(isset($identified_user)) $tmp36=$reg8log_db->quote_smart($identified_user);
		else if(isset($banned_user)) $tmp36=$reg8log_db->quote_smart($banned_user);
		else $tmp36=$reg8log_db->quote_smart($logged_out_user);
		require_once $index_dir.'include/code/code_db_object.php';
		require_once $index_dir.'include/func/func_random.php';
		$new_autologin_key=random_string(43);
		$query="update `accounts` set `autologin_key`='$new_autologin_key'";
		if(isset($flag)) $query.=', `last_activity`='.$req_time;
		if($log_last_logout) $query.=', `last_logout`='.$req_time;
		$query.=' where `username`='.$tmp36.' limit 1';
		$reg8log_db->query($query);
	}
}

if($log_last_logout and !$change_autologin_key_upon_logout) {
	require_once $index_dir.'include/code/code_identify.php';
	if(isset($identified_user) or isset($banned_user) or isset($logged_out_user)) {
		if(isset($identified_user)) $tmp36=$reg8log_db->quote_smart($identified_user);
		else if(isset($banned_user)) $tmp36=$reg8log_db->quote_smart($banned_user);
		else $tmp36=$reg8log_db->quote_smart($logged_out_user);
		require_once $index_dir.'include/code/code_db_object.php';
		$query='update `accounts` set `last_logout`='.$req_time;
		if(isset($flag)) $query.=', `last_activity`='.$req_time;
		$query.=' where `username`='.$tmp36.' limit 1';
		$reg8log_db->query($query);
	}
}

$user=new hm_user($identify_structs);
if($user->logout()) header('Location: index.php');
else {
	$failure_msg=($debug_mode)? $user->err_msg : 'Problem logging out';
	require $index_dir.'include/page/page_failure.php';
	exit;
}

?>
