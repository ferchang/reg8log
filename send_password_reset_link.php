<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
$parent_page=true;

$index_dir='';

require $index_dir.'include/common.php';

require $index_dir.'include/code/code_encoding8anticache_headers.php';

if(!empty($_POST)) {
	require $index_dir.'include/code/code_prevent_xsrf.php';
	require $index_dir.'include/code/code_prevent_repost.php';
}

require $index_dir.'include/info/info_password_change_or_reset.php';

$captcha_needed=true;

if(isset($captcha_needed)) {
	require $index_dir.'include/code/code_sess_start.php';
	$captcha_verified=isset($_SESSION['captcha_verified']);
}

do {//goto statement not supported in PHP < 5.3; so i use do ... while(false) + break in this specific scenario instead.

if(!isset($_POST['email']))  break;

if($_POST['email']==='') $err_msgs[]='Email field is empty!';
else {
	$email_re='/^[a-z0-9_\-+\.]+@([a-z0-9\-+]+\.)+[a-z]{2,5}$/i';
	if(!preg_match($email_re, $_POST['email'])) $err_msgs[]='Email format is invalid!';
}

if(isset($captcha_needed) and !$captcha_verified) require $index_dir.'include/code/code_verify_captcha.php';

if(isset($err_msgs)) break;

require_once $index_dir.'include/code/code_db_object.php';

$time=time();
$expired=$time-$password_reset_period;

$tmp23=$reg8log_db->quote_smart($_POST['email']);
$query1='select * from `password_reset` where `email`='.$tmp23.' and `timestamp`>'.$expired.' limit 1';
$query2='select * from `accounts` where `email`='.$tmp23.' limit 1';

require_once $index_dir.'include/code/code_fetch_site_vars.php';

$lock_name='reg8log--password_reset--'.$site_key;
$reg8log_db->query("select get_lock('$lock_name', -1)");

$email=false;

if($reg8log_db->result_num($query1)) {
  $rec=$reg8log_db->fetch_row();
  $emails_sent=$rec['emails_sent'];
  $email=$rec['email'];
}
else if($reg8log_db->result_num($query2)) {
  $rec=$reg8log_db->fetch_row();
  $emails_sent=0;
  $email=$rec['email'];
  $reg8log_db->query("select release_lock('$lock_name')");
}

if($email) if(!$emails_sent) {//add record to password_reset

$table_name='password_reset';
$field_name='record_id';
require $index_dir.'include/code/code_generate_unique_random_id.php';

$username=$reg8log_db->quote_smart($rec['username']);
$emails_sent=1;
$key=random_string(22);
$timestamp=time();
$email=$reg8log_db->quote_smart($email);

$field_names='`record_id`, `username`, `email`, `emails_sent`, `key`, `timestamp`';

$field_values="'$rid', $username, $email, $emails_sent, '$key', $timestamp";

$query='replace into `password_reset` '."($field_names) values ($field_values)";

$reg8log_db->query($query);

require $index_dir.'include/code/code_email_password_reset_link.php';

$cleanup=true;

}
else if($emails_sent<$max_password_reset_emails or $max_password_reset_emails==-1) {

	$rid=$rec['record_id'];
	$key=$rec['key'];
	require $index_dir.'include/code/code_email_password_reset_link.php';

	if($emails_sent<255) {
		$emails_sent++;
		$query='update ignore `password_reset` set `emails_sent`='.$emails_sent.' where `record_id`='."'{$rec['record_id']}'";
		$reg8log_db->query($query);
	}

}

if(isset($captcha_needed)) unset($_SESSION['captcha_verified']);

require $index_dir.'include/code/code_set_submitted_forms_cookie.php';

$success_msg='<h3>An email is sent to <span style="white-space: pre; color: #080;">'.htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8').'</span>,<br>if that is the correct email address of your account.</h3>';
$no_specialchars=true;
require $index_dir.'include/page/page_success.php';

if(isset($cleanup)) {
	require $index_dir.'include/info/info_cleanup.php';
	if(mt_rand(1, floor(1/$cleanup_probability))==1) require $index_dir.'include/code/code_password_reset_expired_cleanup.php';
}

exit;

} while(false);

require $index_dir.'include/page/page_password_reset_request_form.php';

?>