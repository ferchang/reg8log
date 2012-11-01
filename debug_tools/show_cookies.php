<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
$parent_page=true;

$index_dir='../';

require_once $index_dir.'include/common.php';

require $index_dir.'include/code/code_encoding8anticache_headers.php';

echo '<h3 align="center">reg8log cookies:</h3><hr>';

foreach($_COOKIE as $name=>$value) if(strpos($name, 'reg8log_')===0) {
  echo "<b>cookie name:</b> $name";
  echo "<br><b>cookie value:</b> ";
  $value=htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
  $value=str_replace("\n", '<span style="color: red">\n</span>', $value);
  echo $value;
  echo '<br><b>size:</b> ', strlen($value);
  echo '<hr>';
}

?>

<center><a href="../index.php">Login page</a></center>
