<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

return array(
	'Crypt_Hash'=>'class_aes_cipher.php',
	'Crypt_Rijndael'=>'class_aes_cipher.php',
	'Crypt_AES'=>'class_aes_cipher.php',
	'bcrypt'=>'class_bcrypt.php',
	'class_loader'=>'class_class_loader.php',
	'config'=>'class_config_loader.php',
	'hm_cookie'=>'class_cookie.php',
	'reg8log_db'=>'class_db.php',
	'db_wrapper'=>'class_db_wrapper.php',
	'func'=>'class_function_loader.php',
	'loader_base'=>'class_loader_base.php',
	'hm_user'=>'class_user.php',
);

?>