<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(!config::get('max_ajax_check_usernames')) return;

$ip=$reg8log_db->quote_smart(func::inet_pton2($_SERVER['REMOTE_ADDR']));

$query='insert into `ajax_check_usernames` (`ip`, `timestamp`) values ('.$ip.', '.$req_time.')';

$reg8log_db->query($query);

if(config::get('reset_clients_ajax_check_usernames_upon_register')) {
	$insert_id=mysql_insert_id();
	if(!isset($_COOKIE['reg8log_ajax_check_usernames'])) $cookie_contents=$insert_id;
	else {
		$cookie_contents=$_COOKIE['reg8log_ajax_check_usernames'].",".$insert_id;
		$cookie_contents=implode(",", array_slice(explode(",", $cookie_contents), -20));
	}
	setcookie('reg8log_ajax_check_usernames', $cookie_contents, 0, '/', null, HTTPS, true);	
}

if(mt_rand(1, floor(1/config::get('cleanup_probability')))===1) require ROOT.'include/code/cleanup/code_ajax_check_usernames_expired_cleanup.php';

if(mt_rand(1, floor(1/config::get('cleanup_probability')))===1) require ROOT.'include/code/cleanup/code_ajax_check_usernames_size_cleanup.php';

?>
