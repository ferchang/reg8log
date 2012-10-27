<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");
$parent_page=true;

require_once $index_dir.'include/class/class_cookie.php';
require_once $index_dir.'include/class/class_user.php';
require_once $index_dir.'include/info/info_identify.php';
require_once $index_dir.'include/func/func_random.php';

unset($user);

$user=new hm_user($identify_structs);

unset($identified_user);
unset($identify_error);

if(isset($manual_identify)) {
	require_once $index_dir.'include/func/func_secure_hash.php';
	if($user->identify($manual_identify['username'], $manual_identify['password']) and !isset($banned_user)) $identified_user=$user->user_info['username'];
}
else if($user->identify() and !isset($banned_user)) $identified_user=$user->user_info['username'];

if($user->err_msg) $identify_error=true;

$log_activity=0;

if($log_last_login and isset($manual_identify) and (isset($identified_user) or isset($banned_user))) $log_activity+=1;

if($log_last_activity and (isset($identified_user) or isset($banned_user))) $log_activity+=2;

if($log_activity) require_once $index_dir.'include/code/code_log_last_login8activity.php';

if(isset($banned_user) and !isset($pass_banned_user)) {
	$_identified_username=$banned_user;
	require $index_dir.'include/code/code_dec_incorrect_logins.php';
	require $index_dir.'include/page/page_banned_user.php';
	exit;
}

if(isset($identified_user) and $identified_user=='Admin' and !isset($ajax)) require $index_dir.'include/code/code_check_admin_visit_alerts.php';

?>
