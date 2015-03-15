<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$old_session_settings['cookie_lifetime']=session_get_cookie_params();
$old_session_settings['cookie_lifetime']=$old_session_settings['cookie_lifetime']['lifetime'];
session_set_cookie_params(0);
$old_session_settings['use_cookies']=ini_set('session.use_cookies', '1');
$old_session_settings['use_only_cookies']=ini_set('session.use_only_cookies', '1');
$old_session_settings['gc_maxlifetime']=ini_set('session.gc_maxlifetime', $identify_structs['session']['gc_maxlifetime']);
if($identify_structs['session']['save_path']) $old_session_settings['session_save_path']=session_save_path($identify_structs['session']['save_path']);
$old_session_settings['session_name']=session_name('reg8log_session');
$old_session_settings['session_id']=session_id();
$old_session_settings['httponly']=ini_set("session.cookie_httponly", 1);
$old_session_settings['trans_sid']=ini_set("session.use_trans_sid", 0);
if(HTTPS) $old_session_settings['cookie_secure']=ini_set('session.cookie_secure', 'on');

?>