<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(!isset($index_dir)) $index_dir='';

$headers="MIME-Version: 1.0\nContent-Type: multipart/alternative; boundary=\"==boundary\"";

$link=$_SERVER['HTTP_HOST'];

if($_action=='approve') $tmp24="Congratulations! Site admin approved your registration.";
else $tmp24="Sorry! Site admin rejected your registration.";

$body=$tmp24;
$body.="\n--==Multipart_Boundary\nContent-Type: text/plain; charset=\"utf-8\"";
$body.="\n\n$tmp24\n$link";
$body.="\n--==boundary\nContent-Type: text/html; charset=\"utf-8\"";
$body.="\n\n<html><body><center>$tmp24<br><a href=\"http://$link\">$link</a></center></body></html>\n--==boundary--";

mail($_email, ($_action=='approve')? 'Site admin approved your registration' : 'Site admin rejected your registration', $body, $headers);

?>