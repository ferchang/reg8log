<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

class func {
 
	private static $index=array();
 
    public static function __callStatic($x__func_name, $x__arguments) {
		
		
		
		if(empty($index) and isset($_SESSION['reg8log']['function_index'])) func::$index=$_SESSION['reg8log']['function_index'];
		
		if(empty(func::$index) or !array_key_exists($x__func_name, func::$index)) func::update_index();
		
		if(!array_key_exists($x__func_name, func::$index)) exit("class func: error: '$x__func_name' function file not found!");
		
		//echo func::$index[$x__func_name];
		
		require_once ROOT.'include/func/'.func::$index[$x__func_name];
		
		if(!function_exists($x__func_name)) {//i.e. function is deleted or moved into a different file
			func::update_index();
			if(!array_key_exists($x__func_name, func::$index)) exit("class func: error: '$x__func_name' function file not found!");
			require_once ROOT.'include/func/'.func::$index[$x__func_name];
		}
		
		foreach(get_defined_vars() as $name => $value) {
			$GLOBALS[$name] = &$$name;
		}
		
        return call_user_func_array($x__func_name, $x__arguments);
    }
	
	private static function update_index() {
	
		func::$index=array();
		
		foreach(glob(ROOT.'include/func/func_*.php') as $filename) {
		
			$contents=file_get_contents($filename);
			preg_match_all('#^\h*function\h*([0-9a-z_]*)\h*\(#im', $contents, $matches);
			foreach($matches[1] as $func_name) func::$index[$func_name]=basename($filename);
		
		}
		
		$_SESSION['reg8log']['function_index']=func::$index;
		
		/* echo '<pre>function index updated:<br>';
		print_r(func::$index);
		echo '</pre><br><hr>'; */
	
	}
 
}

?>
