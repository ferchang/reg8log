<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$query='select * from `accounts` where `username`='.$GLOBALS['reg8log_db']->quote_smart($_username);

$GLOBALS['reg8log_db']->query($query);

$tmp13=new hm_user(config::get('identify_structs'));

$tmp13->user_info=$GLOBALS['reg8log_db']->fetch_row();

$tmp13->save_identity(config::get('login_upon_register_age'), false, true);

?>
