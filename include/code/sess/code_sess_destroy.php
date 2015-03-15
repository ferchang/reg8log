<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

setcookie('reg8log_session', false, mktime(12,0,0,1, 1, 1990), '/', null, HTTPS, true);

session_destroy();

require ROOT.'include/code/sess/code_restore_old_sess_settings.php';

?>