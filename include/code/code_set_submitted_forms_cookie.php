<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

require_once ROOT.'include/class/class_cookie.php';

$cookie=new hm_cookie('reg8log_submitted_forms');
$cookie->secure=HTTPS;
$tmp14=$cookie->get();

if($tmp14!==false) $tmp14=$tmp14.','.$_POST['form_id'];
else $tmp14=$_POST['form_id'];

$tmp14=implode(',', array_slice(explode(',', $tmp14), -20));

$cookie->set(null, $tmp14);

?>