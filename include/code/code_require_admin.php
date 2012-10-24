<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

require $index_dir.'/include/code/code_identify.php';

if(!isset($identified_user) or $identified_user!=='Admin') exit('<center><h3>You are not authenticated as Admin!<br>First log in as Admin.</h3><a href="../index.php">Login page</a></center>');

?>