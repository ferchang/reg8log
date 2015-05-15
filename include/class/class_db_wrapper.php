<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

class db_wrapper {

	public static $db_obj=null;
	
	public static function __callStatic($func_name, $arguments) {
		
        return call_user_func_array('db_wrapper::db_obj', $arguments);
		
    }

}

?>
