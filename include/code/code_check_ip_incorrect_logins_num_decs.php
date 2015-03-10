<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$limit=$ip_block_threshold-1;

if($limit<1) return;

$check_decs_lock="'reg8log--check_dec-".$user->user_info['auto']."--$site_key'";
$reg8log_db->query("select get_lock($check_decs_lock, -1)");

if(!isset($is_pending_account)) $is_pending_account=0;

$query='select sum(`num_dec`) as `num_decs` from `ip_incorrect_logins_decs` where `ip`!='.$ip.' and `account_auto`='.$user->user_info['auto'].' and `timestamp`>'.($req_time-$ip_block_period)." and `pending_account`=$is_pending_account";

if(!$reg8log_db->result_num($query)) return;

$rec4=$reg8log_db->fetch_row();

$limit-=$rec4['num_decs'];

?>