<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

require_once $index_dir.'include/code/email/code_emails_header.php';

if($_action=='approve') $tmp24=tr('Congratulations! Site admin approved your registration.', false, $_lang);
else $tmp24=tr('Sorry! Site admin rejected your registration.', false, $_lang);

$body=$tmp24;
$body.="\r\n--==Multipart_Boundary\r\nContent-Type: text/plain; charset=\"utf-8\"";
$body.="\r\n\r\n$tmp24";
$body.="\r\n--==$boundary\r\nContent-Type: text/html; charset=\"utf-8\"\r\n\r\n";

if($_lang=='fa') $body.="<html dir='rtl'><body dir='rtl'>";
else $body.="<html><body>";

$body.="<h3 align='center'>$tmp24<br><br><a href=\"http://$host\">$host</a></h3></body></html>\r\n--==$boundary--";

$subject=($_action=='approve')? tr('Your registration was approved', false, $_lang) : tr('Your registration was rejected', false, $_lang);

mail($_email, '=?UTF-8?B?'.base64_encode($subject).'?=', $body, $headers);

?>