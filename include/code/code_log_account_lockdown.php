<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(!isset($index_dir)) $index_dir='';

require_once $index_dir.'include/info/info_security_logs.php';

require_once $index_dir.'include/code/code_db_object.php';

require_once $index_dir.'include/func/func_inet_pton.php';

$ip=$reg8log_db->quote_smart(inet_pton2($_SERVER['REMOTE_ADDR']));

$query='insert into `account_lockdown_log` (`ext_auto`, `username`, `last_attempt`, `ip`) values ('."$insert_id, $_username, $req_time, $ip)";

$reg8log_db->query($query);

require_once $index_dir.'include/info/info_cleanup.php';

if($keep_expired_block_log_records_for!=0 and mt_rand(1, floor(1/$cleanup_probability))==1) require $index_dir.'include/code/code_account_lockdown_log_expired_cleanup.php';

if(mt_rand(1, floor(1/$cleanup_probability))==1) require $index_dir.'include/code/code_account_lockdown_log_size_cleanup.php';

?>