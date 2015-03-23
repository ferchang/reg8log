<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(isset($block_bypass_mode)) require_once ROOT.'include/code/dec/code_dec_block_bypass_incorrect_logins.php';
else {
	require_once ROOT.'include/code/dec/code_dec_account_incorrect_logins.php';
	require_once ROOT.'include/code/dec/code_dec_ip_incorrect_logins.php';
}

?>