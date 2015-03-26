<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$new_autologin_key=func::random_string(43);
$query="update `accounts` set `autologin_key`='".$new_autologin_key."' where `username`=".$reg8log_db->quote_smart($identified_user).' limit 1';
$reg8log_db->query($query);

$user->user_info['autologin_key']=$new_autologin_key;

$user->save_identity($user->autologin_cookie_expiration, true);

?>
