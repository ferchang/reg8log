<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

//-------------------------------

$site_key=func::random_string(43);

$query="insert ignore into `site_vars` (`name`, `value`) values ('site_key', '$site_key')";

$GLOBALS['reg8log_db']->query($query);

echo sprintf(func::tr('variable created msg'), 'site_key'), ".<br>";

//-------------------------------

$site_key2=func::random_string(43);

$query="insert ignore into `site_vars` (`name`, `value`) values ('site_key2', '$site_key2')";

$GLOBALS['reg8log_db']->query($query);

echo sprintf(func::tr('variable created msg'), 'site_key2'), ".<br>";

//-------------------------------

$site_encr_key=func::random_string(32, '0123456789abcdef');

$query="insert ignore into `site_vars` (`name`, `value`) values ('site_encr_key', '$site_encr_key')";

$GLOBALS['reg8log_db']->query($query);

echo sprintf(func::tr('variable created msg'), 'site_encr_key'), ".<br>";

//-------------------------------

$query="insert ignore into `site_vars` (`name`, `value`) values ('site_salt', '".SITE_SALT."')";

$GLOBALS['reg8log_db']->query($query);

echo sprintf(func::tr('variable created msg'), 'site_salt'), ".<br>";

//-------------------------------

$site_priv_salt=func::random_string(22);

$query="insert ignore into `site_vars` (`name`, `value`) values ('site_priv_salt', '$site_priv_salt')";

$GLOBALS['reg8log_db']->query($query);

echo sprintf(func::tr('variable created msg'), 'site_priv_salt'), ".<br>";

//-------------------------------

$query="insert ignore into `site_vars` (`name`, `value`) values ('entropy', '".ENTROPY."')";

$GLOBALS['reg8log_db']->query($query);

echo sprintf(func::tr('variable created msg'), 'entropy'), ".<br>";

//-------------------------------

?>