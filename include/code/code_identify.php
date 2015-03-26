<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

unset($user);

$user=new hm_user(config::get('identify_structs'));

unset($identified_user);
unset($identify_error);

if(isset($manual_login)) {
	if($user->identify($_POST['username'], $manual_login['password']) and !isset($banned_user) and !isset($pending_user)) $identified_user=$user->user_info['username'];
}
else if($user->identify() and !isset($banned_user) and !isset($pending_user)) $identified_user=$user->user_info['username'];

if($user->err_msg) $identify_error=true;

$log_activity=0;

if(config::get('log_last_login') and isset($manual_login) and (isset($identified_user) or isset($banned_user))) $log_activity+=1;

if(config::get('log_last_activity') and (isset($identified_user) or isset($banned_user)) and !isset($block_bypass_mode)) $log_activity+=2;

if($log_activity) require_once ROOT.'include/code/log/code_log_last_login8activity.php';

if(isset($banned_user) and !isset($pass_banned_user)) {
	$_identified_username=$banned_user;

	require ROOT.'include/code/dec/code_dec_incorrect_logins.php';
	
	require ROOT.'include/page/page_banned_user.php';
	exit;
}

if(isset($identified_user) and $identified_user=='Admin' and !isset($ajax)) require ROOT.'include/code/admin/code_check_admin_visit_alerts.php';

?>
