<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(config::get('account_blocks_alert_threshold')===1) {
	$account_blocks_alert_threshold_reached=true;
	return;
}

if(strpos(config::get('account_blocks_alert_threshold'), '%')===0) {

	$percent=substr(config::get('account_blocks_alert_threshold'), 1);

	$query="select count(*) from `accounts`";	
	$num_accounts=$GLOBALS['reg8log_db']->count_star($query);
	
	$calculated_threshold=ceil($num_accounts*($percent/100));
	
	if($new_account_blocks<$calculated_threshold) return;
	
	$query='select * from `account_block_log` where `last_attempt`>='.(REQUEST_TIME-config::get('account_blocks_alert_threshold_period'));
	if($GLOBALS['reg8log_db']->result_num($query)>=$calculated_threshold)  $account_blocks_alert_threshold_reached=true;
	
	return;
}

if($new_account_blocks<config::get('account_blocks_alert_threshold')) return;

$query='select * from `account_block_log` where `last_attempt`>='.(REQUEST_TIME-config::get('account_blocks_alert_threshold_period'));

if($GLOBALS['reg8log_db']->result_num($query)>=config::get('account_blocks_alert_threshold')) $account_blocks_alert_threshold_reached=true;

?>