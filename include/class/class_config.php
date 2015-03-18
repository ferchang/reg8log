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
				if(isset($_SESSION['cant_use_config_cache_file'])) continue;
				$cache_file=ROOT.self::$cache_file;
				if(self::is_file_accessible($cache_file, 'read') and self::is_cache_valid('file')) {
						echo 'reading config vars from cache file...';
						self::$vars=unserialize(file_get_contents($cache_file));
						if(self::$cache_method[0]==='sess') self::update_cache('sess');
						break;
				}
			}
			else {
				if(isset($_SESSION['config_cache']) and self::is_cache_valid('sess')) {
					echo 'reading config vars from session...';
					self::$vars=$_SESSION['config_cache'];
					if(self::$cache_method[0]==='file' and !isset($_SESSION['cant_use_config_cache_file'])) self::update_cache('file');
					break;
				}
			}
		}
			
		if(empty(self::$vars)) {
			echo 'reading config vars from original config files...';
			foreach(glob(ROOT.'include/config/config_*.php') as $filename) require $filename;
			unset($filename, $tmp18, $username_php_re, $username_js_re);
			foreach(get_defined_vars() as $name => $value) self::$vars[$name]=$value;
			foreach(self::$cache_method as $method) if($method==='sess' or !isset($_SESSION['cant_use_config_cache_file'])) self::update_cache($method);
		}
		
		if(isset(self::$vars[$var_name])) return self::$vars[$var_name];
		else trigger_error("reg8log: class config: '$var_name' not found!", E_USER_ERROR);
		
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
	
	private static function is_file_accessible($file, $op) {
		
		switch($op) {
			case 'read':
				if(!file_exists($file)) return false;
				if(!is_readable($file)) {
					self::report_file_access_error('config cache file not readable');
					return false;
				}
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
		$_SESSION['cant_use_config_cache_file']=true;
	}
	
	//=========================================================================
	
	private static function is_cache_valid($method) {
		
		if($method==='file') $cache_time=filemtime(ROOT.self::$cache_file);
		else $cache_time=$_SESSION['config_cache']['cache_time'];
		
		foreach(glob(ROOT.'include/config/config_*.php') as $filename) if(filemtime($filename)>$cache_time) return false;

		return true;
		
	}
	
	//=========================================================================

}

?>
