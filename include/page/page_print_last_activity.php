<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

echo '<table>';

if($log_last_login and $user->user_info['last_login']) {
	require_once ROOT.'include/func/func_duration2friendly_str.php';
	echo '<tr  ><th>', func::tr('Your last logged login'), ':</th><td>', date ('D j F Y', $user->user_info['last_login']), ' (', duration2friendly_str($req_time-$user->user_info['last_login'], 2), func::tr(' ago'),')</td></tr>';
}

if($log_last_activity and $user->user_info['last_activity']) {
	require_once ROOT.'include/func/func_duration2friendly_str.php';
	echo '<tr ><th>', func::tr('Your last logged activity'), ':</th><td>', date ('D j F Y', $user->user_info['last_activity']), ' (', duration2friendly_str($req_time-$user->user_info['last_activity'], 2), func::tr(' ago'),')</td></tr>';
}

if($log_last_logout and $user->user_info['last_logout']) {
	require_once ROOT.'include/func/func_duration2friendly_str.php';
	echo '<tr ><th>', func::tr('Your last logged logout'), ':</th><td>', date ('D j F Y', $user->user_info['last_logout']), ' (', duration2friendly_str($req_time-$user->user_info['last_logout'], 2), func::tr(' ago'),')</td></tr>';
}

echo '</table>';

?>