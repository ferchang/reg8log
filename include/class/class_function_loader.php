<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

class func {
 
	private static $index=array();
	private static $index_file='file_store/function_index.php';
	
	//=========================================================================
 
    public static function __callStatic($func_name, $arguments) {
		
		func::load_function_definition($func_name);		
        return call_user_func_array($func_name, $arguments);
		
    }
	
	//=========================================================================
	
	public static function load_function_definition($x__func_name) {
			
		if(empty($index)) {
			$x__index_file=ROOT.func::$index_file;
			if(isset($_SESSION['reg8log']['function_index'])) func::$index=$_SESSION['reg8log']['function_index'];
			else if(!isset($_SESSION['reg8log']['cant_use_file_index_file']) and file_exists($x__index_file)) {
					echo "reading index from file...\n";
					if(is_readable($x__index_file)) func::$index=$_SESSION['reg8log']['function_index']=include $x__index_file;
					else func::report_index_file_error('Function index file not readable');
			}
		}
		
		if(empty(func::$index) or !array_key_exists($x__func_name, func::$index)) func::update_index();
		
		if(!array_key_exists($x__func_name, func::$index)) trigger_error("reg8log: class func: error: '$x__func_name' function file not found!", E_USER_ERROR);
		
		include_once ROOT.'include/func/'.func::$index[$x__func_name];
		//don't use require_once, because if a function's file is renamed it will block our function autoloader mechanism. (no index auto-updating will occur because the code execution will stop at this point)
		
		if(!function_exists($x__func_name)) {//function can be deleted or moved into a different file
			func::update_index();
			if(!array_key_exists($x__func_name, func::$index)) trigger_error("reg8log: class func: error: '$x__func_name' function file not found! (2)", E_USER_ERROR);
			require_once ROOT.'include/func/'.func::$index[$x__func_name];
		}
		
		foreach(get_defined_vars() as $name => $value) $GLOBALS[$name] = &$$name;

	}
	
	//=========================================================================
	
	private static function update_index() {
		
		echo "updating index...\n";
		
		func::$index=array();
		
		foreach(glob(ROOT.'include/func/func_*.php') as $filename) {
			$contents=file_get_contents($filename);
			preg_match_all('#^\h*function\h*([0-9a-z_]*)\h*\(#im', $contents, $matches);
			foreach($matches[1] as $func_name) func::$index[$func_name]=basename($filename);
		}
		
		$_SESSION['reg8log']['function_index']=func::$index;
		
		if(isset($_SESSION['reg8log']['cant_use_file_index_file'])) return;
		
		$index_file=ROOT.func::$index_file;
		if(file_exists($index_file)) {
			if(!is_writable($index_file)) {
				func::report_index_file_error('Function index file not writable');
				return;
			}
		}
		else if(!is_writable(dirname($index_file))) {
			func::report_index_file_error('Function index directory not writable');
			return;
		}
		
		$out="<?php\nreturn array(\n";
		foreach(func::$index as $key=>$value) $out.="	'$key'=>'$value',\n";
		$out.=");\n?>";
		file_put_contents($index_file, $out);
	
	}
	
	//=========================================================================
	
	private static function report_index_file_error($str) {
		$_SESSION['reg8log']['cant_use_file_index_file']=true;
		trigger_error("reg8log: class func: $str!", E_USER_NOTICE);
	}
	
	//=========================================================================
 
}

?>
