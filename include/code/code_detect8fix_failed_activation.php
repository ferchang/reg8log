<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(!(($rec['email_verified'] or $rec['email_verification_key']==='') and $rec['admin_confirmed'])) return;

require $index_dir.'include/code/code_activate_pending_account.php';

exit;

?>
