<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
define('CAN_INCLUDE', true);

require 'include/common.php';

if(!empty($_POST)) {
	require ROOT.'include/code/code_prevent_repost.php';
	if(!isset($_POST['form1'])) require ROOT.'include/code/code_prevent_xsrf.php';
}

$captcha_needed=true;

if(isset($captcha_needed)) $captcha_verified=isset($_SESSION['reg8log']['captcha_verified']);

do {//goto statement not supported in PHP < 5.3; so i use do ... while(false) + break in this specific scenario instead.

if(!isset($_POST['email']))  break;

if(isset($_POST['form1'])) break;

if($_POST['email']==='') $err_msgs[]=func::tr('Email field is empty!');
else {
	$email_re='/^[a-z0-9_\-+\.]+@([a-z0-9\-+]+\.)+[a-z]{2,5}$/i';
	if(!preg_match($email_re, $_POST['email'])) $err_msgs[]=func::tr('Email format is invalid!');
}

if(isset($captcha_needed) and !$captcha_verified) require ROOT.'include/code/code_verify_captcha.php';

if(isset($err_msgs)) break;

$expired1=$req_time-config::get('email_verification_time');
$expired2=$req_time-config::get('admin_confirmation_time');

$tmp21=$reg8log_db->quote_smart($_POST['email']);
$query='select * from `pending_accounts` where `email`='.$tmp21." and (`email_verification_key`!='' and `email_verified`=0 and `timestamp`>".$expired1.') and (`admin_confirmed`=1 or `timestamp`>'.$expired2.') limit 1';

if($reg8log_db->result_num($query)) {
  $rec=$reg8log_db->fetch_row();
  $emails_sent=$rec['emails_sent'];
  $email=$rec['email'];
}
else $email=false;

if($email) {
	if(config::get('max_activation_emails')===-1 or $emails_sent<config::get('max_activation_emails')) {
		$rid=$rec['record_id'];
		$email_verification_key=$rec['email_verification_key'];
		require ROOT.'include/code/email/code_email_verification_link.php';
		if($emails_sent<255) {
			$emails_sent++;
			$tmp21=$reg8log_db->quote_smart($rec['username']);
			$query='update `pending_accounts` set `emails_sent`='.$emails_sent.' where `username`='.$tmp21.' limit 1';
			$reg8log_db->query($query);
		}
	}
}

if(isset($captcha_needed)) unset($_SESSION['reg8log']['captcha_verified']);

require ROOT.'include/code/code_set_submitted_forms_cookie.php';

$success_msg='<h3>'.sprintf(func::tr('verification email sent msg'), htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8'));
if(!isset($_POST['identified'])) $success_msg.=func::tr('verification email sent msg p2').'</h3>';
else  $success_msg.='.</h3>';
$no_specialchars=true;
require ROOT.'include/page/page_success.php';
exit;

} while(false);

require ROOT.'include/page/page_activation_email_request_form.php';

?>