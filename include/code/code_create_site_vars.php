<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

//-------------------------------

$site_key=random_string(43);

$query="insert ignore into `site_vars` (`name`, `value`) values ('site_key', '$site_key')";

$reg8log_db->query($query);

echo "Variable <span style=\"color: green\">site_key</span> created.<br>";

//-------------------------------

$site_key2=random_string(43);

$query="insert ignore into `site_vars` (`name`, `value`) values ('site_key2', '$site_key2')";

$reg8log_db->query($query);

echo "Variable <span style=\"color: green\">site_key2</span> created.<br>";

//-------------------------------

$site_encr_key=random_string(32, '0123456789abcdef');

$query="insert ignore into `site_vars` (`name`, `value`) values ('site_encr_key', '$site_encr_key')";

$reg8log_db->query($query);

echo "Variable <span style=\"color: green\">site_encr_key</span> created.<br>";

//-------------------------------

$query="insert ignore into `site_vars` (`name`, `value`) values ('site_salt', '$site_salt')";

$reg8log_db->query($query);

echo "Variable <span style=\"color: green\">site_salt</span> created.<br>";

//-------------------------------

$site_priv_salt=random_string(22);

$query="insert ignore into `site_vars` (`name`, `value`) values ('site_priv_salt', '$site_priv_salt')";

$reg8log_db->query($query);

echo "Variable <span style=\"color: green\">site_priv_salt</span> created.<br>";

//-------------------------------

$query="insert ignore into `site_vars` (`name`, `value`) values ('entropy', '$entropy')";

$reg8log_db->query($query);

echo "Variable <span style=\"color: green\">entropy</span> created.<br>";

//-------------------------------

?>