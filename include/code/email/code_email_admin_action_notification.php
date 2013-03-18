<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

$headers="MIME-Version: 1.0\nContent-Type: multipart/alternative; boundary=\"==boundary\"";

$link=$_SERVER['HTTP_HOST'];

if($_action=='approve') $tmp24=tr('Congratulations! Site admin approved your registration.');
else $tmp24=tr('Sorry! Site admin rejected your registration.');

$body=$tmp24;
$body.="\n--==Multipart_Boundary\nContent-Type: text/plain; charset=\"utf-8\"";
$body.="\n\n$tmp24\n$link";
$body.="\n--==boundary\nContent-Type: text/html; charset=\"utf-8\"";
$body.="\n\n<html $page_dir><body $page_dir><center>$tmp24<br><a href=\"http://$link\">$link</a></center></body></html>\n--==boundary--";

mail($_email, ($_action=='approve')? tr('Your registration was approved') : tr('Your registration was rejected'), $body, $headers);

?>