<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

require ROOT.'include/code/code_identify.php';

if(!isset($identified_user) or $identified_user!=='Admin') func::my_exit('<center><h3>'.func::tr('You are not Admin!').'</h3><a href="../index.php">'.func::tr('Login page').'</a></center>');

?>