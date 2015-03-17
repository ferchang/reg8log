<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

class config {

	private static $vars=array();
	
	//=========================================================================
	
	public static function v($var_name) {
		
		if(empty(self::$vars)) {
			echo 'loading config vars...';
			foreach(glob(ROOT.'include/config/config_*.php') as $filename) require $filename;
			unset($filename, $tmp18, $username_php_re, $username_js_re);
			foreach(get_defined_vars() as $name => $value) self::$vars[$name] = $value;
		}
		
		if(isset(self::$vars[$var_name])) return self::$vars[$var_name];
		else trigger_error("reg8log: class config: error: '$var_name' not found!", E_USER_ERROR);
		
    }
		
	//=========================================================================

}

?>
