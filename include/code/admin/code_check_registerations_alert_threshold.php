<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

if($registerations_alert_threshold==1 or (!$registerations_alert_threshold_period and $new_registerations>=$registerations_alert_threshold)) {
	$registerations_alert_threshold_reached=true;
	return;
}

if($new_registerations<$registerations_alert_threshold) return;

require_once $index_dir.'include/code/code_db_object.php';

$query='select 1 from `registerations_history` where `timestamp`>='.($req_time-$registerations_alert_threshold_period);

if($reg8log_db->result_num($query)>=$registerations_alert_threshold) $registerations_alert_threshold_reached=true;

?>