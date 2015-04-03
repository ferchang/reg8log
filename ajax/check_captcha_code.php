<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
define('CAN_INCLUDE', true);

define('AJAX', true);

require_once '../include/common.php';

require ROOT.'include/code/code_prevent_xsrf.php';

//sleep(1);

require ROOT.'include/code/code_verify_captcha.php';
if(isset($captcha_err)) echo 'n';
else echo 'y';

?>