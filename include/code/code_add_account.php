<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$table_name='accounts';
$field_name='uid';
require ROOT.'include/code/code_generate_unique_random_id.php';

$autologin_key=func::random_string(43);

if($_POST['password']!=='') 
$_fields['password']['value']=bcrypt::hash($_POST['password']);

$field_names='`uid`, `autologin_key`, `timestamp`, ';
$field_values="'$rid', '$autologin_key', ".REQUEST_TIME.', ';
$i=0;

unset($_fields['captcha']);
$_fields['password_hash']=$_fields['password'];
unset($_fields['password']);
foreach($_fields as $field_name=>$specs) {
  $field_names.="`$field_name`";
  $field_values.=$GLOBALS['reg8log_db']->quote_smart($specs['value']);
  if(++$i===count($_fields)) break;
  $field_names.=', ';
  $field_values.=', ';
}

$query="insert into `accounts` ($field_names) values ($field_values)";

$GLOBALS['reg8log_db']->query($query);
unset($_SESSION['reg8log']['captcha_verified'], $_SESSION['reg8log']['passed']);

$success_msg=func::tr('account created msg');
$no_specialchars=true;

?>
