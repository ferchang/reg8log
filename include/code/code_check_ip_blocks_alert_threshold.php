<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

if($ip_blocks_alert_threshold==1) {
	$ip_blocks_alert_threshold_reached=true;
	return;
}

if(strpos($ip_blocks_alert_threshold, '%')===0) {

	$percent=substr($ip_blocks_alert_threshold, 1);

	require_once $index_dir.'include/code/code_db_object.php';

	$query="select * from `accounts`";	
	$num_accounts=$reg8log_db->result_num($query);
	
	$calculated_threshold=ceil($num_accounts*($percent/100));
	
	if($new_ip_blocks<$calculated_threshold) return;
	
	$query='select * from `ip_block_log` where `last_attempt`>='.(time()-24*60*60);
	if($reg8log_db->result_num($query)>=$calculated_threshold)  $ip_blocks_alert_threshold_reached=true;
	
	return;
}

if($new_ip_blocks<$ip_blocks_alert_threshold) return;

require_once $index_dir.'include/code/code_db_object.php';

$query='select * from `ip_block_log` where `last_attempt`>='.(time()-24*60*60);

if($reg8log_db->result_num($query)>=$ip_blocks_alert_threshold) $ip_blocks_alert_threshold_reached=true;

?>