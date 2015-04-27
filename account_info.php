<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
define('CAN_INCLUDE', true);

require 'include/common.php';

require ROOT.'include/code/code_identify.php';

if(!isset($identified_user)) func::my_exit('<center><h3>'.func::tr('You are not authenticated msg').'.</h3><a href="index.php">'.func::tr('Login page').'</a></center>');

echo '<br><center><h3>Not implemented yet.</h3></center>';

?>