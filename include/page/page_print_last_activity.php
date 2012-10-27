<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

require_once $index_dir.'include/func/duration2friendly_str.php';

echo '<b>Your last logged activity:</b> ', date ('D j F Y', $user->user_info['last_activity']), ' (', duration2friendly_str(time()-$user->user_info['last_activity'], 2), ' ago',')';

echo '<br>';

?>