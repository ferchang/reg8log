<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(!$max_ajax_check_usernames) return;

require_once $index_dir.'include/func/func_inet.php';

$ip=$reg8log_db->quote_smart(inet_pton2($_SERVER['REMOTE_ADDR']));

$query='insert into `ip_ajax_check_usernames` (`ip`, `timestamp`) values ('.$ip.', '.time().')';

$reg8log_db->query($query);

if($reset_clients_ajax_check_usernames_upon_register) {
	$insert_id=mysql_insert_id();
	if(!isset($_COOKIE['reg8log_ajax_check_usernames'])) $cookie_contents=$insert_id;
	else {
		$cookie_contents=$_COOKIE['reg8log_ajax_check_usernames'].",".$insert_id;
		$cookie_contents=implode(",", array_slice(explode(",", $cookie_contents), -20));
	}
	setcookie('reg8log_ajax_check_usernames', $cookie_contents, 0, '/', null, $https, true);	
}

require_once $index_dir.'include/config/config_cleanup.php';

if(mt_rand(1, floor(1/$cleanup_probability))==1) require $index_dir.'include/code/code_ip_ajax_check_usernames_expired_cleanup.php';

if(mt_rand(1, floor(1/$cleanup_probability))==1) require $index_dir.'include/code/code_ip_ajax_check_usernames_size_cleanup.php';

?>
