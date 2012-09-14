<?php

if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

$identify_structs=array(

//------------------------------------

	'autologin_cookie'=>array(

		'long_age'=>365*24*60*60, //in seconds
		/*
		$long_age value is used as the default age for permanent autologin cookies.
		*/

		'value_seperator'=>'-',//string used to seperate values in auto-login cookie

		'uid',
		'autologin_key'

	),

//-----------------------------------

	'session'=>array(

		'save_path'=>'',

		'gc_maxlifetime'=>''

	)

//------------------------------------

);

$change_autologin_key_upon_login=1; // possible values: 0, 1, 2.
// 0: no
// 1: upon manual login
// 2: upon both manual and auto-login

/*

- with option 1, if the user's autologin cookie is stealed (or someone reaches a machine on which the user is logged in), the attacker will be logged out the next time the legitimate user logs in manually into his account (from anywhere).
Note that if the user himself uses more than one PC (e.g. at work and home), with this option he can be logged in only on one of them at every moment.
Note: with this option, a database update operation is needed every time the user logs in.

- option 2 can be used when higher security is needed, but notice that it needs a database update operation on every request for each logged in user.

*/

$log_last_activity=true;

?>
