<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");



require_once $index_dir.'include/func/func_random.php';

$autos='';
$i=0;
foreach($appr as $auto) {
  $autos.="$auto";
  if(++$i==count($appr)) break;
  $autos.=", ";
}

$query='update `pending_accounts` set `admin_confirmed`=1 where `auto` in ('.$autos.')';

$reg8log_db->query($query);

$nonexistent_records=0;

foreach($appr as $auto) {

	$query="select * from `pending_accounts` where `auto`=$auto";

	if(!$reg8log_db->result_num($query)) {
	  $nonexistent_records++;
	  continue;
	}

	$rec=$reg8log_db->fetch_row();

	if(isset($_POST['email-'.$auto])) $emails[]=$_POST['email-'.$auto];

	if($rec['email_verification_key']!=='' and !$rec['email_verified']) continue;

	$tmp2[]=$auto;

	$table_name='accounts';
	$field_name='uid';
	require $index_dir.'include/code/code_generate_unique_random_id.php';

	$autologin_key=random_string(43);

	$field_names='`uid`, `username`, `password_hash`, `email`, `gender`, `autologin_key`, `timestamp`';

	$username=$reg8log_db->quote_smart($rec['username']);
	$email=$reg8log_db->quote_smart($rec['email']);
	$gender=$rec['gender'];
	$timestamp=$req_time; // should we use $rec['timestamp'] instead?
	$password_hash=$reg8log_db->quote_smart($rec['password_hash']);

	$field_values="'$rid', $username, $password_hash, $email, '$gender', '$autologin_key', $timestamp";

	$query="insert into `accounts` ($field_names) values ($field_values)";
	$reg8log_db->query($query);

}

if(isset($tmp2)) {
	$autos='';
	$i=0;
	foreach($tmp2 as $auto) {
	  $autos.="$auto";
	  if(++$i==count($tmp2)) break;
	  $autos.=", ";
	}
	$query='delete from `pending_accounts` where `auto` in ('.$autos.')';
	$reg8log_db->query($query);
}

if(isset($emails)) foreach($emails as $_email) {
	$_action='approve';
	require $index_dir.'include/code/code_email_admin_action_notification.php';
}

unset($emails);

$queries_executed=true;

?>
