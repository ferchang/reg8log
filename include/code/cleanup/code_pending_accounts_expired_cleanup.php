<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

if($email_verification_needed) {
$expired=$req_time-$email_verification_time;
$query="delete from `pending_accounts` where `email_verification_key`!='' and `email_verified`=0 and `timestamp` < $expired";
$reg8log_db->query($query);
}
else if(mt_rand(1, 10)==1) {
$expired=$req_time-$email_verification_time;
$query="delete from `pending_accounts` where `email_verification_key`!='' and `email_verified`=0 and `timestamp` < $expired";
$reg8log_db->query($query);
}

if($admin_confirmation_needed) {
$expired=$req_time-$admin_confirmation_time;
$query="delete from `pending_accounts` where `admin_confirmed`=0 and `timestamp` < $expired";
$reg8log_db->query($query);
}
else if(mt_rand(1, 10)==1) {
$expired=$req_time-$admin_confirmation_time;
$query="delete from `pending_accounts` where `admin_confirmed`=0 and `timestamp` < $expired";
$reg8log_db->query($query);
}

?>