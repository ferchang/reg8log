<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");
define('CAN_INCLUDE', true);

$query='select * from `email_change` where `username`='.$reg8log_db->quote_smart($identified_user).' limit 1';

//--------------
if($max_change_email_emails==-1) {
	if($max_activation_emails==-1) $max_emails=0;
	else $max_emails=$max_activation_emails;
}
else $max_emails=$max_change_email_emails;

if($change_email_verification_time==0) $verification_time=$email_verification_time;
else $verification_time=$change_email_verification_time;
//--------------

if($reg8log_db->result_num($query)) {
	$rec=$reg8log_db->fetch_row();
	$rid=$rec['record_id'];
	$emails_sent=$rec['emails_sent']+1;
	if($emails_sent>255) $emails_sent=255;
	if($_POST['newemail']!=$rec['email']) {
		
		$email_verification_key=func::random_string(22);
	}
	else $email_verification_key=$rec['email_verification_key'];
	if($rec['timestamp']>$req_time-$verification_time) {
		if($max_emails and $rec['emails_sent']>=$max_emails) {
			$max_emails_reached=true;
			return;
		}
		$timestamp=$rec['timestamp'];
	}
	else {
		$timestamp=$req_time;
		$emails_sent=1;
		
		$email_verification_key=func::random_string(22);
	}
	$query="update `email_change` set `email`=".$reg8log_db->quote_smart($_POST['newemail']).", `emails_sent`=$emails_sent, `email_verification_key`='$email_verification_key', `timestamp`=$timestamp";
	$reg8log_db->query($query);
	require ROOT.'include/code/email/code_email_change_email_verification_link.php';
	
	return;
}

//--------------------

$table_name='email_change';
$field_name='record_id';
require ROOT.'include/code/code_generate_unique_random_id.php';

$username=$reg8log_db->quote_smart($identified_user);
$email=$reg8log_db->quote_smart($_POST['newemail']);
$email_verification_key=func::random_string(22);

$query="replace into `email_change` (`record_id`, `username`, `email`, `emails_sent`, `email_verification_key`, `timestamp`) values ('$rid', $username, $email, 1, '$email_verification_key', $req_time)";

$reg8log_db->query($query);
require ROOT.'include/code/email/code_email_change_email_verification_link.php';

//-------------
require_once ROOT.'include/config/config_cleanup.php';
if(mt_rand(1, floor(1/config::get('cleanup_probability')))==1) require ROOT.'include/code/cleanup/code_email_change_expired_cleanup.php';
//-------------

?>
