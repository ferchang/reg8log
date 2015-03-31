<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(isset($_COOKIE['reg8log_submitted_forms'], $_POST['form_id'])) {
  if(in_array($_POST['form_id'], explode(',', $_COOKIE['reg8log_submitted_forms']), true)) {
    $failure_msg='<h3>'.func::tr('Your request is already processed!').'</h3>';
    $no_specialchars=true;
    require ROOT.'include/page/page_failure.php';
    exit;
  }
}
?>