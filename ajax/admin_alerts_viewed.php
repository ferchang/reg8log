<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
define('CAN_INCLUDE', true);

require_once '../include/common.php';

require ROOT.'include/code/code_prevent_xsrf.php';

$ajax=true;
require ROOT.'include/code/admin/code_require_admin.php';

if(!isset($_POST['new_account_blocks'])) exit('new_account_blocks parameter is not set');
if(!isset($_POST['new_ip_blocks'])) exit('new_ip_blocks parameter is not set');
if(!isset($_POST['new_registerations'])) exit('new_registerations parameter is not set');

if(!is_numeric($_POST['new_account_blocks'])) exit('new_account_blocks parameter is not numeric');
if(!is_numeric($_POST['new_ip_blocks'])) exit('new_ip_blocks parameter is not numeric');
if(!is_numeric($_POST['new_registerations'])) exit('new_registerations parameter is not numeric');

if($_POST['new_account_blocks']) {
	$query="update `admin_block_alerts` set `new_account_blocks`=`new_account_blocks`-{$_POST['new_account_blocks']} where `for`='visit' and `new_account_blocks`>={$_POST['new_account_blocks']} limit 1";
	$reg8log_db->query($query);
}

if($_POST['new_ip_blocks']) {
	$query="update `admin_block_alerts` set `new_ip_blocks`=`new_ip_blocks`-{$_POST['new_ip_blocks']} where `for`='visit' and `new_ip_blocks`>={$_POST['new_ip_blocks']} limit 1";
	$reg8log_db->query($query);
}

if($_POST['new_registerations']) {
	$query="update `admin_registeration_alerts` set `new_registerations`=`new_registerations`-{$_POST['new_registerations']} where `for`='visit' and `new_registerations`>={$_POST['new_registerations']} limit 1";
	$reg8log_db->query($query);
}

echo 'ok';

?>