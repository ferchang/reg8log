<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(!config::get('block_bypass_max_incorrect_logins')) return;

if(!isset($_COOKIE['reg8log_block_bypass_incorrect_logins']) or strpos($_COOKIE['reg8log_block_bypass_incorrect_logins'], $block_bypass_record_auto.',')!==0) return;

$tmp33=substr($_COOKIE['reg8log_block_bypass_incorrect_logins'], strpos($_COOKIE['reg8log_block_bypass_incorrect_logins'], ',')+1);

if(!is_numeric($tmp33)) return;

$query="update `block_bypass` set `incorrect_logins`=`incorrect_logins`-$tmp33 where `auto`=".$block_bypass_record_auto." and `incorrect_logins`>=$tmp33 limit 1";

$GLOBALS['reg8log_db']->query($query);

setcookie('reg8log_block_bypass_incorrect_logins', false, mktime(12,0,0,1, 1, 1990), '/', null, HTTPS, true);

?>