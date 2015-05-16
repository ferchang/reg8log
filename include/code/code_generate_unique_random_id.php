<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$query="select * from `$table_name` where `$field_name`";
$i=0;
$random_ids=array();
do {
do {
$rid=func::random_string(8);
if($i++>1000) {
$failure_msg=(config::get('debug_mode'))? $GLOBALS['reg8log_db']->err_msg : "Unable to generate a random unique  id.";
require ROOT.'include/page/page_failure.php';
exit;
}
} while(in_array($rid, $random_ids));
$random_ids[]=$rid;
$i++;
} while($GLOBALS['reg8log_db']->result_num("$query='$rid' limit 1"));

?>