<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
$parent_page=true;

$index_dir='./';

require $index_dir.'include/common.php';

require $index_dir.'include/code/code_encoding8anticache_headers.php';

require $index_dir.'include/code/code_db_object.php';

require_once $index_dir.'include/func/func_random.php';

for($i=0; $i<50; $i++) {

	$uid=random_string(8);

	$username=random_string(8);
	
	$email=random_string(8);

	$query="insert into accounts values(null, '$uid', '$username', '', '$email', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)";
	
	$reg8log_db->query($query);
	
}

echo 'ok';

?>
