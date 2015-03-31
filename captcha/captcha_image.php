<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
define('CAN_INCLUDE', true);

define('CAPTCHA_IMG', true);//used in class_config_loader.php

require_once '../include/common.php';

func::captcha_show_image();

?>