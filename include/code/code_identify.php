<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");
$parent_page=true;

if(!isset($index_dir)) $index_dir='';

require_once $index_dir.'include/class/class_cookie.php';
require_once $index_dir.'include/class/class_user.php';
require $index_dir.'include/info/info_identify.php';
require_once $index_dir.'include/func/func_random.php';

unset($user);

$user=new hm_user($identify_structs);

$identified_username=null;
$identify_error=false;

if(isset($manual_identify)) {
	require_once $index_dir.'include/func/func_secure_hash.php';
	if($user->identify($manual_identify['username'], $manual_identify['password']))
	$identified_username=$user->user_info['username'];
}
else if($user->identify()) $identified_username=$user->user_info['username'];

if($user->err_msg) $identify_error=true;

?>
