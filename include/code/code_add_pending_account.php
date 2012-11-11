<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

require_once $index_dir.'include/code/code_db_object.php';

require_once $index_dir.'include/func/func_random.php';

require_once $index_dir.'include/func/func_secure_hash.php';

if($_POST['password']!=='') 
$fields['password']['value']=create_secure_hash($_POST['password']);

$table_name='pending_accounts';
$field_name='record_id';
require $index_dir.'include/code/code_generate_unique_random_id.php';

$field_names='`record_id`, ';
$field_values="'$rid', ";

unset($fields['captcha']);
$fields['password_hash']=$fields['password'];
unset($fields['password']);
foreach($fields as $field_name=>$specs) {
  $field_names.="`$field_name`";
  $field_values.=$reg8log_db->quote_smart($specs['value']);
  $field_names.=', ';
  $field_values.=', ';
}

if($email_verification_needed) $email_verification_key=random_string(22);
else $email_verification_key='';

if($admin_confirmation_needed) $admin_confirmed=0;
else $admin_confirmed=1;

$field_names.='`emails_sent`, `email_verification_key`, `email_verified`, `admin_confirmed`, `timestamp`, `notify_user`';

if($email_verification_needed) $emails_sent=1;
else $emails_sent=0;

if($can_notify_user_about_admin_action and isset($_POST['notify'])) $notify_user=1;
else $notify_user=0;

$field_values.="$emails_sent, '$email_verification_key', 0, $admin_confirmed, $req_time, $notify_user";

$query="replace into `pending_accounts` ($field_names) values ($field_values)";

$reg8log_db->query($query);

unset($_SESSION['captcha_verified'], $_SESSION['passed']);

if($email_verification_needed) {
  require $index_dir.'include/code/email/code_email_verification_link.php';
  require_once $index_dir.'include/func/duration2friendly_str.php';
  $success_msg='<h3>An email containing the account activation link is sent to your email.<br>Complete your registration by opening that link in '.duration2friendly_str($email_verification_time, 0).'.<br>If you received no email, you can log into your pending account and request a new email.</h3>';
}
else if($admin_confirmation_needed) {
  require_once $index_dir.'include/func/duration2friendly_str.php';
  $success_msg="<h3>Thank you!<br>Your request is processed successfully and your account is pending for admin's confirmation in the next ".duration2friendly_str($admin_confirmation_time, 0).'.</h3>';
}

$no_specialchars=true;

require $index_dir.'include/config/config_cleanup.php';

if(mt_rand(1, floor(1/$cleanup_probability))==1) {
	require_once $index_dir.'include/code/code_fetch_site_vars.php';
	$reg8log_db->query("select release_lock('$lock_name')");
	require $index_dir.'include/code/cleanup/code_pending_accounts_expired_cleanup.php';
}

?>