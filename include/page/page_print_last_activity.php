<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(!isset($index_dir)) $index_dir='';

require_once $index_dir.'include/func/func_duration2msg.php';

echo '<b>Your last logged activity:</b> ', date ('D j F Y', $user->user_info['last_activity']), ' (', duration2friendly_str(time()-$user->user_info['last_activity'], 2), ' ago',')';

echo '<br>';

?>