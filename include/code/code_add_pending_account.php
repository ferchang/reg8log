<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

require_once ROOT.'include/code/code_db_object.php';

if($_POST['password']!=='') 
$_fields['password']['value']=bcrypt::hash($_POST['password']);

$table_name='pending_accounts';
$field_name='record_id';
require ROOT.'include/code/code_generate_unique_random_id.php';

$field_names='`record_id`, ';
$field_values="'$rid', ";

unset($_fields['captcha']);
$_fields['password_hash']=$_fields['password'];
unset($_fields['password']);
foreach($_fields as $field_name=>$specs) {
  $field_names.="`$field_name`";
  $field_values.=$reg8log_db->quote_smart($specs['value']);
  $field_names.=', ';
  $field_values.=', ';
}

if(config::get('email_verification_needed')) $email_verification_key=func::random_string(22);
else $email_verification_key='';

if(config::get('admin_confirmation_needed')) $admin_confirmed=0;
else $admin_confirmed=1;

$field_names.='`emails_sent`, `email_verification_key`, `email_verified`, `admin_confirmed`, `timestamp`, `notify_user`, `lang`';

if(config::get('email_verification_needed')) $emails_sent=1;
else $emails_sent=0;

if(config::get('can_notify_user_about_admin_action') and isset($_POST['notify'])) $notify_user=1;
else $notify_user=0;

$field_values.="$emails_sent, '$email_verification_key', 0, $admin_confirmed, $req_time, $notify_user, '".config::get('lang')."'";

$query="replace into `pending_accounts` ($field_names) values ($field_values)";

$reg8log_db->query($query);

unset($_SESSION['reg8log']['captcha_verified'], $_SESSION['reg8log']['passed']);

if(config::get('email_verification_needed')) {
  require ROOT.'include/code/email/code_email_verification_link.php';
  
  $success_msg=sprintf(func::tr('account activation email sent msg'), func::duration2friendly_str(config::get('email_verification_time'), 0));
}
else if(config::get('admin_confirmation_needed')) {
  
  $success_msg=sprintf(func::tr('pending for admin confirmation msg'), func::duration2friendly_str(config::get('admin_confirmation_time'), 0));
}

$no_specialchars=true;

if(mt_rand(1, floor(1/config::get('cleanup_probability')))==1) {
	require_once ROOT.'include/code/code_fetch_site_vars.php';
	$reg8log_db->query("select release_lock('$lock_name')");
	require ROOT.'include/code/cleanup/code_pending_accounts_expired_cleanup.php';
}

?>