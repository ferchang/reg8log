<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$query="select `email` from `accounts` where `username`='Admin' limit 1";

$GLOBALS['reg8log_db']->query($query);

$rec3=$GLOBALS['reg8log_db']->fetch_row();

$email=$rec3['email'];

require_once ROOT.'include/code/email/code_emails_header.php';

$body="$admin_alert_email_msg\r\n\r\n";
$body.="\r\n--==Multipart_Boundary\r\nContent-Type: text/plain; charset=\"utf-8\"";
$body.="\r\n\r\n$admin_alert_email_msg\r\n\r\n";
$body.="\r\n--==$boundary\r\nContent-Type: text/html; charset=\"utf-8\"\r\n\r\n";

if(config::get('admin_emails_lang')==='fa') $body.="<html dir='rtl'><body dir='rtl'>";
else $body.="<html><body>";

$body.="<h3 align='center'>".str_replace("\n", '<br>', $admin_alert_email_msg)."<br><a href=\"http://$host\">$host</a><br><br></h3></body></html>\r\n--==$boundary--";

mail($email, '=?UTF-8?B?'.base64_encode(func::tr('Account/IP blocks alert', false, config::get('admin_emails_lang'))).'?=', $body, $headers);

if(config::get('debug_mode')) echo "Emailed: $admin_alert_email_msg";

?>