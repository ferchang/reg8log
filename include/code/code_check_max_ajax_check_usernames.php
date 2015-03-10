<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(!$max_ajax_check_usernames) return;

require_once ROOT.'include/func/func_inet.php';

$ip=$reg8log_db->quote_smart(inet_pton2($_SERVER['REMOTE_ADDR']));

$query='select count(*) as `n` from `ajax_check_usernames` where `ip`='.$ip.' and `timestamp`>='.($req_time-$max_ajax_check_usernames_period);

$reg8log_db->query($query);

$rec=$reg8log_db->fetch_row();

if($rec['n']>=$max_ajax_check_usernames) exit('$max_ajax_check_usernames is reached for your IP!');

?>
