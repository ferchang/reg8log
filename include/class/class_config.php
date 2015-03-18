<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

class config {

	private static $vars=array();
	private static $cache_method=array('file', 'sess');
	private static $cache_file='file_store/config_cache.php';
	
	//=========================================================================
	
	public static function v($var_name) {
		
		foreach(self::$cache_method as $method) {
			if($method==='file') {
				$cache_file=ROOT.self::$cache_file;
				if(self::is_file_accessible($cache_file, 'read') and self::is_cache_valid()) {
						echo 'reading config vars from cache file...';
						self::$vars=unserialize(file_get_contents($cache_file));
						break;
				}
			}
			else {
				if(isset($_SESSION['config_cache']) and self::is_cache_valid()) {
					echo 'reading config vars from session...';
					self::$vars=$_SESSION['config_cache'];
					break;
				}
			}
		}
			
		if(empty(self::$vars)) {
			echo 'reading config vars from original config files...';
			foreach(glob(ROOT.'include/config/config_*.php') as $filename) require $filename;
			unset($filename, $tmp18, $username_php_re, $username_js_re);
			foreach(get_defined_vars() as $name => $value) self::$vars[$name]=$value;
			if(!empty(self::$cache_method)) self::update_cache();
		}
		
		if(isset(self::$vars[$var_name])) return self::$vars[$var_name];
		else trigger_error("reg8log: class config: '$var_name' not found!", E_USER_ERROR);
		
    }
		
	//=========================================================================

	private static function update_cache() {
		foreach(self::$cache_method as $method) {
			if($method==='file') {
				$cache_file=ROOT.self::$cache_file;
				if(!self::is_file_accessible($cache_file, 'write')) continue;
				file_put_contents($cache_file, serialize(self::$vars));
				break;
			}
			else {
				$_SESSION['config_cache']=self::$vars;
				break;
			}
		}
	}
		
	//=========================================================================
	
	private static function is_file_accessible($file, $op) {
		
		switch($op) {
			case 'read':
				if(!file_exists($file) or !is_readable($file)) return false;
			break;
			case 'write':
				if(file_exists($file)) {
					if(!is_writable($file)) {
						self::report_file_access_error('config cache file not writable');
						return false;
					}
				}
				else if(!is_writable(dirname($file))) {
					self::report_file_access_error('config cache directory not writable');
					return false;
				}
			break;
		}
		
		return true;
		
	}
	
	//=========================================================================
	
	private static function report_file_access_error($str) {
		trigger_error("reg8log: class config: $str!", E_USER_NOTICE);
	}
	
	//=========================================================================
	
	private static function is_cache_valid() {
		return true;
	}
	
	//=========================================================================

}

?>
