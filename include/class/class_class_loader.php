<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

class class_loader {
 
	private static $index=array();
 
    public static function load($class_name) {
		
		if(empty($index) and isset($_SESSION['reg8log']['class_index'])) class_loader::$index=$_SESSION['reg8log']['class_index'];
		
		if(empty(class_loader::$index) or !array_key_exists($class_name, class_loader::$index)) class_loader::update_index();
		
		if(!array_key_exists($class_name, class_loader::$index)) exit("class class_loader: error: '$class_name' class file not found!");
		
		//echo func::$index[$x__func_name];
		
		include_once ROOT.'include/class/'.class_loader::$index[$class_name];
		//don't use require_once, because if a function's file is renamed it will block our function autoloader mechanism. (no index auto-updating will occur because the code execution will stop at this point)
		
		if(!class_exists($class_name, false)) {//i.e. class is deleted or moved into a different file
			class_loader::update_index();
			if(!array_key_exists($class_name, class_loader::$index)) exit("class class_loader: error: '$class_name' class file not found!");
			require_once ROOT.'include/class/'.class_loader::$index[$class_name];
		}
		
    }
	
	private static function update_index() {
	
		class_loader::$index=array();
		
		foreach(glob(ROOT.'include/class/class_*.php') as $filename) {
		
			$contents=file_get_contents($filename);
			preg_match_all('#^\h*class\h*([0-9a-z_]*)\h*\{#im', $contents, $matches);
			foreach($matches[1] as $class_name) class_loader::$index[$class_name]=basename($filename);
		
		}
		
		$_SESSION['reg8log']['class_index']=class_loader::$index;
		
		/* echo '<pre>function index updated:<br>';
		print_r(func::$index);
		echo '</pre><br><hr>'; */
	
	}
 
}

spl_autoload_register('class_loader::load');

?>
