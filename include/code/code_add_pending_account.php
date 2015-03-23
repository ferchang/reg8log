<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

require_once ROOT.'include/code/code_db_object.php';

require_once ROOT.'include/func/func_random.php';

if($_POST['password']!=='') 
$fields['password']['value']=bcrypt::hash($_POST['password']);

$table_name='pending_accounts';
$field_name='record_id';
require ROOT.'include/code/code_generate_unique_random_id.php';

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

$field_names.='`emails_sent`, `email_verification_key`, `email_verified`, `admin_confirmed`, `timestamp`, `notify_user`, `lang`';

if($email_verification_needed) $emails_sent=1;
else $emails_sent=0;

if($can_notify_user_about_admin_action and isset($_POST['notify'])) $notify_user=1;
else $notify_user=0;

$field_values.="$emails_sent, '$email_verification_key', 0, $admin_confirmed, $req_time, $notify_user, '$lang'";

$query="replace into `pending_accounts` ($field_names) values ($field_values)";

$reg8log_db->query($query);

unset($_SESSION['reg8log']['captcha_verified'], $_SESSION['reg8log']['passed']);

if($email_verification_needed) {
  require ROOT.'include/code/email/code_email_verification_link.php';
  require_once ROOT.'include/func/func_duration2friendly_str.php';
  $success_msg=sprintf(func::tr('account activation email sent msg'), duration2friendly_str($email_verification_time, 0));
}
else if($admin_confirmation_needed) {
  require_once ROOT.'include/func/func_duration2friendly_str.php';
  $success_msg=sprintf(func::tr('pending for admin confirmation msg'), duration2friendly_str($admin_confirmation_time, 0));
}

$no_specialchars=true;

require ROOT.'include/config/config_cleanup.php';

if(mt_rand(1, floor(1/$cleanup_probability))==1) {
	require_once ROOT.'include/code/code_fetch_site_vars.php';
	$reg8log_db->query("select release_lock('$lock_name')");
	require ROOT.'include/code/cleanup/code_pending_accounts_expired_cleanup.php';
}

?>