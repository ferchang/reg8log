<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(!empty($_SERVER['HTTP_HOST'])) $host=$_SERVER['HTTP_HOST'];
else $host=$_SERVER['SERVER_ADDR'];

require_once ROOT.'include/func/func_random.php';
$boundary=random_string(22);

$headers="MIME-Version: 1.0\r\nContent-Type: multipart/alternative; boundary=\"==$boundary\"";
$headers.="\r\nFrom: reg8log <noreply@$host>";

?>