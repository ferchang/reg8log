<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

$query="select count(*) as `n` from `accounts` where `username`!='Admin'";

$reg8log_db->query($query);

$rec=$reg8log_db->fetch_row();

$accounts=$rec['n'];

//---------------

$query='select count(*) as `n` from `accounts` where `banned`=1 or `banned`>='.$req_time;

$reg8log_db->query($query);

$rec=$reg8log_db->fetch_row();

$banned_users=$rec['n'];

//---------------

require $index_dir.'include/config/config_register.php';

$expired1=$req_time-$email_verification_time;
$expired2=$req_time-$admin_confirmation_time;

$query="select count(*) as `n` from `pending_accounts` where (`email_verification_key`='' or `email_verified`=1 or `timestamp`>=".$expired1.') and (`admin_confirmed`=0 and `timestamp`>='.$expired2.')';

$reg8log_db->query($query);

$rec=$reg8log_db->fetch_row();

$pending_accounts4admin=$rec['n'];

//---------------

$query="select count(*) as `n` from `pending_accounts` where (`email_verification_key`!='' and `email_verified`=0 and `timestamp`>=".$expired1.') and (`admin_confirmed`=1 or `timestamp`>='.$expired2.')';

$reg8log_db->query($query);

$rec=$reg8log_db->fetch_row();

$pending_accounts4email=$rec['n'];


//---------------

$query="select count(*) as `n` from `account_block_log`";

$reg8log_db->query($query);

$rec=$reg8log_db->fetch_row();

$all_account_blocks=$rec['n'];

require $index_dir.'include/config/config_brute_force_protection.php';

$query="select count(*) as `n` from `account_block_log` where `first_attempt`>".($req_time-$account_block_period);

$reg8log_db->query($query);

$rec=$reg8log_db->fetch_row();

$active_account_blocks=$rec['n'];

//---------------

$query="select count(*) as `n` from `ip_block_log`";

$reg8log_db->query($query);

$rec=$reg8log_db->fetch_row();

$all_ip_blocks=$rec['n'];

$query="select count(*) as `n` from `ip_block_log` where `first_attempt`>".($req_time-$ip_block_period);

$reg8log_db->query($query);

$rec=$reg8log_db->fetch_row();

$active_ip_blocks=$rec['n'];

?>