<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(!isset($index_dir)) $index_dir='';

require_once $index_dir.'include/class/class_cookie.php';

$cookie=new hm_cookie('reg8log_submitted_forms');
$cookie->secure=$https;
$tmp14=$cookie->get();

if($tmp14!==false) $tmp14=$tmp14.','.$_POST['form_id'];
else $tmp14=$_POST['form_id'];

$tmp14=implode(',', array_slice(explode(',', $tmp14), -20));

$cookie->set(null, $tmp14);

?>