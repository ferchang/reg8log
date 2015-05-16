<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(!config::get('max_ajax_check_usernames')) return;

$ip=$GLOBALS['reg8log_db']->quote_smart(func::inet_pton2($_SERVER['REMOTE_ADDR']));

$query='select count(*) as `n` from `ajax_check_usernames` where `ip`='.$ip.' and `timestamp`>='.(REQUEST_TIME-config::get('max_ajax_check_usernames_period'));

$GLOBALS['reg8log_db']->query($query);

$rec=$GLOBALS['reg8log_db']->fetch_row();

if($rec['n']>=config::get('max_ajax_check_usernames')) exit('$max_ajax_check_usernames is reached for your IP!');

?>
