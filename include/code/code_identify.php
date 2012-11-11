<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");
$parent_page=true;

require_once $index_dir.'include/class/class_cookie.php';
require_once $index_dir.'include/class/class_user.php';
require_once $index_dir.'include/config/config_identify.php';
require_once $index_dir.'include/func/func_random.php';

unset($user);

$user=new hm_user($identify_structs);

unset($identified_user);
unset($identify_error);

if(isset($manual_login)) {
	require_once $index_dir.'include/func/func_secure_hash.php';
	if($user->identify($_POST['username'], $manual_login['password']) and !isset($banned_user) and !isset($pending_user)) $identified_user=$user->user_info['username'];
}
else if($user->identify() and !isset($banned_user) and !isset($pending_user)) $identified_user=$user->user_info['username'];

if($user->err_msg) $identify_error=true;

$log_activity=0;

if($log_last_login and isset($manual_login) and (isset($identified_user) or isset($banned_user))) $log_activity+=1;

if($log_last_activity and (isset($identified_user) or isset($banned_user)) and !isset($block_bypass_mode)) $log_activity+=2;

if($log_activity) require_once $index_dir.'include/code/log/code_log_last_login8activity.php';

if(isset($banned_user) and !isset($pass_banned_user)) {
	$_identified_username=$banned_user;

	require $index_dir.'include/code/dec/code_dec_incorrect_logins.php';
	
	require $index_dir.'include/page/page_banned_user.php';
	exit;
}

if(isset($identified_user) and $identified_user=='Admin' and !isset($ajax)) require $index_dir.'include/code/admin/code_check_admin_visit_alerts.php';

?>
