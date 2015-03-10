<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

require_once ROOT.'include/code/email/code_emails_header.php';

if($_action=='approve') $tmp24=func::tr('Congratulations! Site admin approved your registration.', false, $_lang);
else $tmp24=func::tr('Sorry! Site admin rejected your registration.', false, $_lang);

$take_no_action_msg=func::tr('email_take_no_action_msg', false, $_lang);

$body=$take_no_action_msg."\r\n\r\n".$tmp24."\r\n\r\n";
$body.="\r\n--==Multipart_Boundary\r\nContent-Type: text/plain; charset=\"utf-8\"";
$body.="\r\n\r\n$take_no_action_msg\r\n\r\n$tmp24\r\n\r\n";
$body.="\r\n--==$boundary\r\nContent-Type: text/html; charset=\"utf-8\"\r\n\r\n";

if($_lang=='fa') $body.="<html dir='rtl'><body dir='rtl'>";
else $body.="<html><body>";

$body.="<h3 align='center'>$take_no_action_msg<br><br>$tmp24<br><br><a href=\"http://$host\">$host</a><br><br></h3></body></html>\r\n--==$boundary--";

$subject=($_action=='approve')? func::tr('Your registration was approved', false, $_lang) : func::tr('Your registration was rejected', false, $_lang);

mail($_email, '=?UTF-8?B?'.base64_encode($subject).'?=', $body, $headers);

?>