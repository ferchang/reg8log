<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(!$max_ajax_check_usernames or !$reset_clients_ajax_check_usernames_upon_register) return;

if(!isset($_COOKIE['reg8log_ajax_check_usernames'])) return;

$tmp34=explode(',', $_COOKIE['reg8log_ajax_check_usernames']);

foreach($tmp34 as $auto) if(!is_numeric($auto)) return;

require_once $index_dir.'include/func/func_inet.php';

$ip=$reg8log_db->quote_smart(inet_pton2($_SERVER['REMOTE_ADDR']));

$query='delete from `ajax_check_usernames` where `auto` in ('.$_COOKIE['reg8log_ajax_check_usernames'].') and `ip`='.$ip.' limit '.$max_ajax_check_usernames;

$reg8log_db->query($query);

setcookie('reg8log_ajax_check_usernames', false, mktime(12,0,0,1, 1, 1990), '/', null, $https, true);

?>