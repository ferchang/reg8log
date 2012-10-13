<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");



if(strpos($refill, "hashed-$site_salt")===0) {
	require_once $index_dir.'include/func/func_encryption_with_site8client_keys.php';
	$refill_output=' value="encrypted-'.$site_salt.'-'.base64_encode(encrypt($refill)).'" ';
}
else if(strpos($refill, "encrypted-$site_salt")===0) $refill_output=' value="'.$refill.'" ';
else if($password_refill===2) {
	$refill='hashed-'.$site_salt.'-'.hash('sha256', $site_salt.$refill);
	require_once $index_dir.'include/func/func_encryption_with_site8client_keys.php';
	$refill_output=' value="encrypted-'.$site_salt.'-'.base64_encode(encrypt($refill)).'" ';
}
else $refill_output='';

echo $refill_output;

?>
