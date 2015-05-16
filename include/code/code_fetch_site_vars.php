<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$query='select * from `site_vars`';

$GLOBALS['reg8log_db']->auto_abort=false;
$GLOBALS['reg8log_db']->query($query);
$GLOBALS['reg8log_db']->auto_abort=true;

if($GLOBALS['reg8log_db']->err_msg) {
	if(!defined('SETUP_PAGE')) {
		$failure_msg='site_vars fetch query failed!';
		require ROOT.'include/page/page_failure.php';
		exit;
	}
	return;
}

$num_recs=6;

if($GLOBALS['reg8log_db']->result_num()!==$num_recs) {
	if(!defined('SETUP_PAGE')) {
		$failure_msg='<h3>Number of records in site_vars table is incorrect! <small>(Failed setup?)</small></h3>';
		$no_specialchars=true;
		require ROOT.'include/page/page_failure.php';
		exit;
	}
return;
}

while($rec=$GLOBALS['reg8log_db']->fetch_row()) define(strtoupper($rec['name']), $rec['value']);

?>