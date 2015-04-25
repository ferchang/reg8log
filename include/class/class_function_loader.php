<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

class func extends loader_base {
 
	private static $index=array();
	private static $index_file='file_store/function_index.php';
	
	//=========================================================================
 
    public static function __callStatic($func_name, $arguments) {
		
		if(!function_exists($func_name)) self::load_function_definition($func_name);
        return call_user_func_array($func_name, $arguments);
		
    }
	
	//=========================================================================
	
	public static function load_function_definition($x__func_name) {
			
		if(empty(self::$index)) {
			
			$x__index_file=ROOT.self::$index_file;
			if(isset($_SESSION['reg8log']['function_index'])) self::$index=$_SESSION['reg8log']['function_index'];
			else if(!isset($_SESSION['reg8log']['class_'.get_class().'_file_access_error']) and self::is_file_accessible($x__index_file, 'read')) {
					self::debug_msg("function loader: reading index from file...");
					self::$index=$_SESSION['reg8log']['function_index']=include $x__index_file;
			}
		}
		
		if(empty(self::$index) or !array_key_exists($x__func_name, self::$index)) self::update_index();
		
		if(!array_key_exists($x__func_name, self::$index)) trigger_error("reg8log: class func: error: '$x__func_name' function file not found!", E_USER_ERROR);
		
		include_once ROOT.'include/func/'.self::$index[$x__func_name];
		//don't use require_once, because if a function's file is renamed it will block our function autoloader mechanism. (no index auto-updating will occur because the code execution will stop at this point)
		
		if(!function_exists($x__func_name)) {//function can be deleted or moved into a different file
			self::update_index();
			if(!array_key_exists($x__func_name, self::$index)) trigger_error("reg8log: class func: error: '$x__func_name' function file not found! (2)", E_USER_ERROR);
			require_once ROOT.'include/func/'.self::$index[$x__func_name];
		}
		
		//foreach(get_defined_vars() as $name => $value) $GLOBALS[$name] = &$$name;

	}
	
	//=========================================================================
	
	private static function update_index() {
		
		self::debug_msg("function loader: updating index...");
		
		self::$index=array();
		
		foreach(glob(ROOT.'include/func/func_*.php') as $filename) {
			$contents=file_get_contents($filename);
			preg_match_all('#^\h*function\h*([0-9a-z_]*).*?\(#sim', $contents, $matches);
			foreach($matches[1] as $func_name) self::$index[$func_name]=basename($filename);
		}
		
		$_SESSION['reg8log']['function_index']=self::$index;
		
		if(isset($_SESSION['reg8log']['class_'.get_class().'_file_access_error'])) return;
		
		$index_file=ROOT.self::$index_file;
		if(!self::is_file_accessible($index_file, 'write')) return;
		
		$out="<?php\nif(ini_get('register_globals')) exit(\"<center><h3>Error: Turn that damned register globals off!</h3></center>\");\nif(!defined('CAN_INCLUDE')) exit(\"<center><h3>Error: Direct access denied!</h3></center>\");";
		$out.="\n\nreturn array(\n";
		foreach(self::$index as $key=>$value) $out.="	'$key'=>'$value',\n";
		$out.=");\n\n?>";
		file_put_contents($index_file, $out, LOCK_EX);
	
	}
	
	//=========================================================================
 
}

?>
