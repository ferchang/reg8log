<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

ini_set('session.use_cookies', $old_session_settings['use_cookies']);
ini_set('session.use_only_cookies', $old_session_settings['use_only_cookies']);
ini_set('session.gc_maxlifetime', $old_session_settings['gc_maxlifetime']);
ini_set('session.cookie_httponly', $old_session_settings['httponly']);
ini_set('session.use_trans_sid', $old_session_settings['trans_sid']);
if(HTTPS) ini_set('session.cookie_secure', $old_session_settings['cookie_secure']);

session_set_cookie_params($old_session_settings['cookie_lifetime']);
if($identify_structs['session']['save_path']) session_save_path($old_session_settings['session_save_path']);
session_name($old_session_settings['session_name']);
session_id($old_session_settings['session_id']);

?>