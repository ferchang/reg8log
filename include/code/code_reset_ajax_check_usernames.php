<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(!config::get('max_ajax_check_usernames') or !config::get('reset_clients_ajax_check_usernames_upon_register')) return;

if(!isset($_COOKIE['reg8log_ajax_check_usernames'])) return;

$tmp34=explode(',', $_COOKIE['reg8log_ajax_check_usernames']);

foreach($tmp34 as $auto) if(!is_numeric($auto)) return;

$ip=$GLOBALS['reg8log_db']->quote_smart(func::inet_pton2($_SERVER['REMOTE_ADDR']));

$query='delete from `ajax_check_usernames` where `auto` in ('.$_COOKIE['reg8log_ajax_check_usernames'].') and `ip`='.$ip.' limit '.config::get('max_ajax_check_usernames');

$GLOBALS['reg8log_db']->query($query);

setcookie('reg8log_ajax_check_usernames', false, mktime(12,0,0,1, 1, 1990), '/', null, HTTPS, true);

?>