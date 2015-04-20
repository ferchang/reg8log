<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
define('CAN_INCLUDE', true);

require_once '../include/common.php';

echo '<h3 align="center">reg8log cookies:</h3><hr>';

foreach($_COOKIE as $name=>$value) if(strpos($name, 'reg8log_')===0) {
  echo "<b>cookie name:</b> $name";
  echo "<br><b>cookie value:</b> ";
  $value=htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
  $value=str_replace("\n", '<span style="color: red">\n</span>', $value);
  echo $value;
  echo '<br><b>size<small>(bytes)</small>:</b> ', strlen($value);
  echo '<hr>';
}

?>

<br><center><a href="../index.php">Login page</a></center>
