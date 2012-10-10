<?php

if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

//----------------------------------------

$keep_expired_block_log_records_for=0;
/*
-1: don't keep expired block log records
0: keep unlimitedly (note that records can still be deleted due to $max_security_logs_records)
a positive integer: keep up to this number of seconds after block expiration
*/

?>