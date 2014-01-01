<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
$parent_page=true;

$index_dir='../';

require_once $index_dir.'include/common.php';

require $index_dir.'include/code/code_encoding8anticache_headers.php';

require $index_dir.'include/code/code_prevent_xsrf.php';

//sleep(1);

require $index_dir.'include/code/code_verify_captcha.php';
if(isset($captcha_err)) echo 'n';
else echo 'y';

?>