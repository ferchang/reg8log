<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(!$block_bypass_max_incorrect_logins) return;

if($incorrect_logins<$block_bypass_max_incorrect_logins and $incorrect_logins<255) {
	$incorrect_logins++;
	$query='update `block_bypass` set `incorrect_logins`=`incorrect_logins`+1 where `username`='.$reg8log_db->quote_smart($_POST['username']).' limit 1';
	$reg8log_db->query($query);

	if(isset($_COOKIE['reg8log_block_bypass_incorrect_logins']) and stripos($_COOKIE['reg8log_block_bypass_incorrect_logins'], strtolower($_POST['username'])."\n")===0) $tmp32=substr($_COOKIE['reg8log_block_bypass_incorrect_logins'], strpos($_COOKIE['reg8log_block_bypass_incorrect_logins'], "\n")+1)+1;
	else $tmp32=1;
	
	setcookie('reg8log_block_bypass_incorrect_logins', strtolower($_POST['username'])."\n".$tmp32, 0, '/', null, $https, true);	
}

if($incorrect_logins>=$block_bypass_max_incorrect_logins) {
	setcookie('reg8log_block_bypass_incorrect_logins', false, mktime(12,0,0,1, 1, 1990), '/', null, $https, true);
	exit('<center><h3>Maximum number of incorrect logins is reached.<br>You cannot use block-bypass system until next block.</h3><br><a href="index.php">Login page</a></center>');
}

?>