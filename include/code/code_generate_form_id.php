<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$form_id=func::random_string(5);

echo '<input type="hidden" name="form_id" value="', $form_id, '">';

?>
