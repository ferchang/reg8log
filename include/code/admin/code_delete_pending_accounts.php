<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

$autos='';
$i=0;
foreach($del as $auto) {
	if(!is_numeric($auto)) exit('error: auto value not numeric!');
	if(isset($_POST['email-'.$auto])) {
		$emails[]=$_POST['email-'.$auto];
		$langs[]=$_POST['lang-'.$auto];
	}
	$autos.="$auto";
	if(++$i==count($del)) break;
	$autos.=", ";
}

$query='delete from `pending_accounts` where `auto` in ('.$autos.')';

$reg8log_db->query($query);

if(isset($emails)) for($j=0; $j<count($emails); $j++) {
	$_email=$emails[$j];
	$_lang=$langs[$j];
	$_action='reject';
	require $index_dir.'include/code/email/code_email_admin_action_notification.php';
}

unset($emails, $langs);

$queries_executed=true;

?>
