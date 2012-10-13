<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");



@ setcookie('reg8log_session', false, mktime(12,0,0,1, 1, 1990), '/', null, $https, true);

@ touch(session_save_path().'/sess_'.session_id(), 0, 0);
@ session_destroy();
@ unlink(session_save_path().'/sess_'.session_id());

ini_set('session.use_cookies', $old_use_cookies);
ini_set('session.use_only_cookies', $old_use_only_cookies);
ini_set('session.gc_maxlifetime', $old_gc_maxlifetime);
ini_set('session.cookie_httponly', $old_httponly);
ini_set('session.use_trans_sid', $old_trans_sid);
if($https) ini_set('session.cookie_secure', $old_cookie_secure);
session_set_cookie_params($old_cookie_lifetime);
session_save_path($old_session_save_path);
session_name($old_session_name);
session_id($old_session_id);

?>