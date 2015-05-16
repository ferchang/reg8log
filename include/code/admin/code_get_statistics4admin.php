<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$query="select count(*) from `accounts` where `username`!='Admin'";

$accounts=$GLOBALS['reg8log_db']->count_star($query);

//---------------

$query='select count(*) from `accounts` where `banned`=1 or `banned`>='.REQUEST_TIME;

$banned_users=$GLOBALS['reg8log_db']->count_star($query);;

//---------------

$expired1=REQUEST_TIME-config::get('email_verification_time');
$expired2=REQUEST_TIME-config::get('admin_confirmation_time');

$query="select count(*) from `pending_accounts` where (`email_verification_key`='' or `email_verified`=1 or `timestamp`>=".$expired1.') and (`admin_confirmed`=0 and `timestamp`>='.$expired2.')';

$pending_accounts4admin=$GLOBALS['reg8log_db']->count_star($query);;

//---------------

$query="select count(*) from `pending_accounts` where (`email_verification_key`!='' and `email_verified`=0 and `timestamp`>=".$expired1.') and (`admin_confirmed`=1 or `timestamp`>='.$expired2.')';

$pending_accounts4email=$GLOBALS['reg8log_db']->count_star($query);

//---------------

$query="select count(*) from `account_block_log`";

$all_account_blocks=$GLOBALS['reg8log_db']->count_star($query);

$query="select count(*) from `account_block_log` where `unblocked`=0 and  ((`username`!='admin' and `first_attempt`>".(REQUEST_TIME-config::get('account_block_period'))." and `block_threshold`>=".config::get('account_block_threshold').") or (`username`='admin' and `first_attempt`>".(REQUEST_TIME-config::get('admin_account_block_period'))." and `block_threshold`>=".config::get('admin_account_block_threshold')."))";

$active_account_blocks=$GLOBALS['reg8log_db']->count_star($query);

//---------------

$query="select count(*) from `ip_block_log`";

$all_ip_blocks=$GLOBALS['reg8log_db']->count_star($query);

$query="select count(*) from `ip_block_log` where `unblocked`=0 and  ((`last_username`!='admin' and `first_attempt`>".(REQUEST_TIME-config::get('ip_block_period'))." and `block_threshold`>=".config::get('ip_block_threshold').") or (`last_username`='admin' and `first_attempt`>".(REQUEST_TIME-config::get('admin_ip_block_period'))." and `block_threshold`>=".config::get('admin_ip_block_threshold')."))";

$active_ip_blocks=$GLOBALS['reg8log_db']->count_star($query);

?>