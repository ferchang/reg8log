<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

class config extends loader_base {

	private static $vars=array();
	private static $cache_method=array('file', 'sess');
	private static $cache_file='file_store/config_cache.txt';//note: this file path is used in setup check_file_permissions.php too. if change it here, change there too.
	private static $cache_valid=false;
	private static $cache_validation_interval=0;
	
	//=========================================================================
	
	public static function get($var_name) {
		
		if(empty(self::$vars)) foreach(self::$cache_method as $method) {
			if($method==='file') {
				if(isset($_SESSION['reg8log']['class_config_file_access_error'])) continue;
				$cache_file=ROOT.self::$cache_file;
				if(self::is_file_accessible($cache_file, 'read') and self::is_cache_valid('file')) {
						echo 'reading config vars from cache file...';
						self::$vars=unserialize(file_get_contents($cache_file));
						if(self::$cache_method[0]==='sess') self::update_cache('sess');
						else unset($_SESSION['config_cache']);
						break;
				}
			}
			else {
				if(isset($_SESSION['config_cache']) and self::is_cache_valid('sess')) {
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
			if(in_array('file', self::$cache_method) and !isset($_SESSION['reg8log']['class_config_file_access_error'])) self::update_cache('file');
			if(in_array('sess', self::$cache_method)) {
				if(self::$cache_method[0]==='sess' or isset($_SESSION['reg8log']['class_config_file_access_error'])) self::update_cache('sess');
			} else unset($_SESSION['config_cache']);
			
		}
		
		if(isset(self::$vars[$var_name])) return self::$vars[$var_name];
		else trigger_error("reg8log: class config: '$var_name' not found!", E_USER_ERROR);
		
    }
		
	//=========================================================================
	
	public static function set($var_name, $var_value) {
		config::get($var_name);
		
	}
	
	//=========================================================================

	private static function update_cache($method) {
		if($method==='file') {
			$cache_file=ROOT.self::$cache_file;
			if(self::is_file_accessible($cache_file, 'write')) file_put_contents($cache_file, serialize(self::$vars));
		}
		else {
			$_SESSION['config_cache']=self::$vars;
			$_SESSION['config_cache']['cache_time']=time();
		}
	}
		
	//=========================================================================
	
	private static function is_cache_valid($method) {
		
		if(self::$cache_valid) return true;
		if(
			self::$cache_validation_interval
			and isset($_SESSION['last_config_cache_validation'])
			and (time()-$_SESSION['last_config_cache_validation'])<=self::$cache_validation_interval
		  ) return true;
		
		echo 'proceeding with cache validation...';
		
		if($method==='file') $cache_time=filemtime(ROOT.self::$cache_file);
		else $cache_time=$_SESSION['config_cache']['cache_time'];
		
		foreach(glob(ROOT.'include/config/config_*.php') as $filename) if(filemtime($filename)>$cache_time) return false;
		
		self::$cache_valid=true;
		if(self::$cache_validation_interval) $_SESSION['last_config_cache_validation']=time();
		return true;
		
	}
	
	//=========================================================================

}

?>
