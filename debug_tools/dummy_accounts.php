<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
define('CAN_INCLUDE', true);

require '../include/common.php';

if(!$debug_mode) exit('<center><h3>Error: Debug mode is off!</h3><a href="../index.php">Login page</a></center>');

require ROOT.'include/code/admin/code_require_admin.php';

if(!empty($_POST)) {

	require ROOT.'include/code/code_prevent_xsrf.php';

	if(isset($_POST['delete_all'])) {
		$deleted=0;
		$query="delete from accounts where password_hash='dummy account'";
		$reg8log_db->query($query);
		$deleted+=mysql_affected_rows();
		$query="delete from pending_accounts where password_hash='dummy account'";
		$reg8log_db->query($query);
		$deleted+=mysql_affected_rows();
		$del_msg=$deleted.' dummy account(s) deleted.';
		require 'page_dummy_accounts_form.php';
		exit;
	}
	
	$num_records=intval($_POST['num']);

	if(isset($_POST['pending4admin'])) $admin_confirmed=0;
	else $admin_confirmed=1;

	if(isset($_POST['pending4email'])) $email_verification_key='x';
	else $email_verification_key='';

	for($i=0; $i<$num_records; $i++) {

		$uid=func::random_string(8);

		$username=func::random_string(8);
		
		$email=func::random_string(8).'@site.com';

		if(!$admin_confirmed or $email_verification_key!=='') $query="insert into pending_accounts values(null, '$uid', '$username', 'dummy account', '$email', '', 0, '$email_verification_key', 0, $admin_confirmed, ".REQUEST_TIME.", 0, '')";
		else $query="insert into accounts values(null, '$uid', '$username', 'dummy account', '$email', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)";
		
		$reg8log_db->query($query);
		
	}

	if($num_records) $msg=$num_records.' dummy account(s) created.';
	
}

require 'page_dummy_accounts_form.php';

?>
