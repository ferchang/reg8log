<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(config::get('ip_blocks_alert_threshold')==1) {
	$ip_blocks_alert_threshold_reached=true;
	return;
}

if(strpos(config::get('ip_blocks_alert_threshold'), '%')===0) {

	$percent=substr(config::get('ip_blocks_alert_threshold'), 1);

	$query="select count(*) from `accounts`";	
	$num_accounts=$reg8log_db->count_star($query);
	
	$calculated_threshold=ceil($num_accounts*($percent/100));
	
	if($new_ip_blocks<$calculated_threshold) return;
	
	$query='select * from `ip_block_log` where `last_attempt`>='.($req_time-config::get('ip_blocks_alert_threshold_period'));
	if($reg8log_db->result_num($query)>=$calculated_threshold)  $ip_blocks_alert_threshold_reached=true;
	
	return;
}

if($new_ip_blocks<config::get('ip_blocks_alert_threshold')) return;

$query='select * from `ip_block_log` where `last_attempt`>='.($req_time-config::get('ip_blocks_alert_threshold_period'));

if($reg8log_db->result_num($query)>=config::get('ip_blocks_alert_threshold')) $ip_blocks_alert_threshold_reached=true;

?>