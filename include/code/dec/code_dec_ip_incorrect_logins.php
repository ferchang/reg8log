<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(config::get('ip_block_threshold')===-1  and config::get('ip_captcha_threshold')===-1) return;

if(!isset($_COOKIE['reg8log_ip_incorrect_logins'])) return;

$ip=$GLOBALS['reg8log_db']->quote_smart(func::inet_pton2($_SERVER['REMOTE_ADDR']));

require ROOT.'include/code/code_check_ip_incorrect_logins_num_decs.php';

if($limit<1) return;

$autos=explode(',', $_COOKIE['reg8log_ip_incorrect_logins']);

$tmp37=$autos;
for($i=0; $i<count($tmp37); $i++) if(!is_numeric($autos[$i])) unset($autos[$i]);
$tmp37=$autos;

$autos=implode(',', $autos);

if(!isset($is_pending_account)) $is_pending_account=0;

$query='delete from `ip_incorrect_logins` where `auto` in '."($autos)".' and `ip`='.$ip.' and `account_auto`='.$user->user_info['auto']." and `pending_account`=$is_pending_account limit $limit";

$GLOBALS['reg8log_db']->query($query);

$affected_rows=mysql_affected_rows();

if($affected_rows===count($tmp37)) setcookie('reg8log_ip_incorrect_logins', false, mktime(12,0,0,1, 1, 1990), '/', null, HTTPS, true);

if($affected_rows<1) return;

$query='insert into `ip_incorrect_logins_decs` (`ip`, `account_auto`, `num_dec`, `timestamp`, `pending_account`) values '."($ip, {$user->user_info['auto']}, $affected_rows, ".REQUEST_TIME.", $is_pending_account)";

$GLOBALS['reg8log_db']->query($query);

if(mt_rand(1, floor(1/config::get('cleanup_probability')))===1) require ROOT.'include/code/cleanup/code_ip_incorrect_logins_decs_expired_cleanup.php';

if(mt_rand(1, floor(1/config::get('cleanup_probability')))===1) require ROOT.'include/code/cleanup/code_ip_incorrect_logins_decs_size_cleanup.php';

?>