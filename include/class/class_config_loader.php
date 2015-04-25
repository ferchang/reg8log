<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

class config extends loader_base {

	private static $vars=array();
	private static $cache_method=array('file', 'sess');
	private static $cache_file='file_store/config_cache.php';//note: this file path is used in setup code_check_file_permissions.php too. if u changed it here, change it there too.
	private static $cache_valid=false;
	
	//=========================================================================
	
	public static function get($var_name) {
		
		if(empty(self::$vars)) foreach(self::$cache_method as $method) {
			if($method==='file') {
				if(isset($_SESSION['reg8log']['class_'.get_class().'_file_access_error'])) continue;
				$cache_file=ROOT.self::$cache_file;
				if(self::is_file_accessible($cache_file, 'read') and self::is_cache_valid('file')) {
						self::debug_msg('config loader: reading config vars from cache file...');
						self::$vars=unserialize(require $cache_file);
						if(self::$cache_method[0]==='sess') self::update_cache('sess');
						else unset($_SESSION['reg8log']['config_cache']);
						break;
				}
			}
			else {
				if(isset($_SESSION['reg8log']['config_cache']) and self::is_cache_valid('sess')) {
					self::debug_msg('config loader: reading config vars from session...');
					self::$vars=$_SESSION['reg8log']['config_cache'];
					break;
				}
			}
		}
			
		if(empty(self::$vars)) {
			self::debug_msg('config loader: reading config vars from original config files...');
			foreach(glob(ROOT.'include/config/config_*.php') as $filename) require $filename;
			unset($filename, $tmp18, $username_php_re, $username_js_re);
			foreach(get_defined_vars() as $name => $value) self::$vars[$name]=$value;
			if(in_array('file', self::$cache_method) and !isset($_SESSION['reg8log']['class_'.get_class().'_file_access_error'])) self::update_cache('file');
			if(in_array('sess', self::$cache_method)) {
				if(self::$cache_method[0]==='sess' or isset($_SESSION['reg8log']['class_'.get_class().'_file_access_error'])) self::update_cache('sess');
			} else unset($_SESSION['reg8log']['config_cache']);
			
		}
		
		if(isset(self::$vars[$var_name])) return self::$vars[$var_name];
		else trigger_error("reg8log: class config: '$var_name' not found!", E_USER_ERROR);
		
    }
		
	//=========================================================================
	
	public static function set($var_name, $var_value) {
		if(!isset(self::$vars[$var_name])) config::get($var_name);
		self::$vars[$var_name]=$var_value;
	}
	
	//=========================================================================

	private static function update_cache($method) {
		if($method==='file') {
			$cache_file=ROOT.self::$cache_file;
			if(self::is_file_accessible($cache_file, 'write')) {
				$out="<?php\nif(ini_get('register_globals')) exit(\"<center><h3>Error: Turn that damned register globals off!</h3></center>\");\nif(!defined('CAN_INCLUDE')) exit(\"<center><h3>Error: Direct access denied!</h3></center>\");";
				$out.="\n\nreturn <<<'REG8LOG_CONFIG_VARS'\n";
				$out.=serialize(self::$vars);
				$out.="\nREG8LOG_CONFIG_VARS;\n\n?>";
				file_put_contents($cache_file, $out, LOCK_EX);
			}
		}
		else {
			$_SESSION['reg8log']['config_cache']=self::$vars;
			$_SESSION['reg8log']['config_cache']['cache_time']=time();
		}
	}
		
	//=========================================================================
	
	private static function is_cache_valid($method) {
		
		if(self::$cache_valid) return true;
		
		if(
			//-----------------------
			!$GLOBALS['debug_mode']
			//-----------------------
			and isset($_SESSION['reg8log']['config_cache_version'])
			and $_SESSION['reg8log']['config_cache_version']===$GLOBALS['config_cache_version']
			//-----------------------
			and $GLOBALS['config_cache_validation_interval']
			and isset($_SESSION['reg8log']['last_config_cache_validation'])
			and (time()-$_SESSION['reg8log']['last_config_cache_validation'])<=$GLOBALS['config_cache_validation_interval']
			//-----------------------
		  ) return true;
		
		self::debug_msg('config loader: proceeding with cache validation...');
		
		if($method==='file') $cache_time=filemtime(ROOT.self::$cache_file);
		else $cache_time=$_SESSION['reg8log']['config_cache']['cache_time'];
		
		foreach(glob(ROOT.'include/config/config_*.php') as $filename) if(filemtime($filename)>$cache_time) return false;
		
		self::$cache_valid=true;
		
		if($GLOBALS['config_cache_validation_interval']) {
			$_SESSION['reg8log']['last_config_cache_validation']=time();
			$_SESSION['reg8log']['config_cache_version']=$GLOBALS['config_cache_version'];
			unset($GLOBALS['session0'], $GLOBALS['session1']);
		}
		
		return true;
		
	}
	
	//=========================================================================

}

?>
