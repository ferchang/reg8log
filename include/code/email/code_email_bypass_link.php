<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

require_once ROOT.'include/code/email/code_emails_header.php';

if(isset($_SERVER['SCRIPT_NAME'])) $dir=$_SERVER['SCRIPT_NAME'];
else if(isset($_SERVER['REQUEST_URI']))  $dir=$_SERVER['REQUEST_URI'];
else $dir=$_SERVER['PHP_SELF'];
$dir=htmlspecialchars($dir, ENT_QUOTES, 'UTF-8');
$dir=pathinfo($dir, PATHINFO_DIRNAME);
$dir=str_replace('\\', '/', $dir);

if(strlen($dir)==1) $dir='';

$link='http://'.$host.$dir.'/block_bypass_login.php?key='.$key;

$take_no_action_msg=func::tr('email_take_no_action_msg');

$body=$take_no_action_msg."\r\n\r\n".func::tr('Block-bypass link').": $link\r\n\r\n";
$body.="\r\n--==Multipart_Boundary\r\nContent-Type: text/plain; charset=\"utf-8\"";
$body.="\r\n\r\n$take_no_action_msg\r\n\r\n".func::tr('Block-bypass link').": $link\r\n\r\n";
$body.="\r\n--==$boundary\r\nContent-Type: text/html; charset=\"utf-8\"\r\n\r\n";
$body.="<html $page_dir><body $page_dir><h3 align='center'>$take_no_action_msg<br><br><a href=\"$link\">".func::tr('Block-bypass link')."</a><br><br></h3></body></html>\r\n--==$boundary--";

mail($email, '=?UTF-8?B?'.base64_encode(func::tr('Block-bypass')).'?=', $body, $headers);

if($debug_mode) echo $link;

?>