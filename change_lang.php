<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
$parent_page=true;

$index_dir='./';

if(isset($_GET['setup'])) $setup_page=true;

require $index_dir.'include/common.php';

if($lang=='en') setcookie('reg8log_lang', 'fa', time()+60*60*24*365, '/', null, $https, true);
else setcookie('reg8log_lang', 'en', time()+60*60*24*365, '/', null, $https, true);

if(isset($_SERVER['HTTP_REFERER'])) header("Location: {$_SERVER['HTTP_REFERER']}");
else if(isset($_GET['addr'])) header("Location: {$_GET['addr']}");
else header("Location: index.php");

?>