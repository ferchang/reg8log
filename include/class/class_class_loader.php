<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

require ROOT.'include/class/class_loader_base.php';

class class_loader extends loader_base {

	private static $index=array();
	private static $index_file='file_store/class_index.php';
	
	//=========================================================================
 
    public static function load($x__class_name) {
		
		if(empty(self::$index)) {
			$x__index_file=ROOT.self::$index_file;
			if(isset($_SESSION['reg8log']['class_index'])) self::$index=$_SESSION['reg8log']['class_index'];
			else if(!isset($_SESSION['reg8log']['class_'.get_class().'_file_access_error']) and self::is_file_accessible($x__index_file, 'read')) {
					self::debug_msg("class loader: reading index from file...");
					self::$index=$_SESSION['reg8log']['class_index']=include $x__index_file;
			}
		}
		
		if(empty(self::$index) or !array_key_exists($x__class_name, self::$index)) self::update_index();
		
		if(!array_key_exists($x__class_name, self::$index)) trigger_error("reg8log: class class_loader: error: '$x__class_name' class file not found!", E_USER_ERROR);
		
		include_once ROOT.'include/class/'.self::$index[$x__class_name];
		//don't use require_once, because if a class's file is renamed it will block our class autoloader mechanism. (no index auto-updating will occur because the code execution will stop at this point)
		
		if(!class_exists($x__class_name)) {//class can be deleted or moved into a different file
			self::update_index();
			if(!array_key_exists($x__class_name, self::$index)) trigger_error("reg8log: class class_loader: error: '$x__class_name' class file not found! (2)", E_USER_ERROR);
			require_once ROOT.'include/class/'.self::$index[$x__class_name];
		}
		
	}
	
	//=========================================================================
	
	private static function update_index() {
		
		self::debug_msg("class loader: updating index...");
		
		self::$index=array();
		
		foreach(glob(ROOT.'include/class/class_*.php') as $filename) {
			$contents=file_get_contents($filename);
			preg_match_all('#^\h*class\h*([0-9a-z_]*).*?\{#sim', $contents, $matches);
			foreach($matches[1] as $class_name) self::$index[$class_name]=basename($filename);
		}
		
		$_SESSION['reg8log']['class_index']=self::$index;
		
		if(isset($_SESSION['reg8log']['class_'.get_class().'_file_access_error'])) return;
		
		$index_file=ROOT.self::$index_file;
		if(!self::is_file_accessible($index_file, 'write')) return;
		
		$out=require ROOT.'include/code/code_php_include_header.php';
		$out.="\n\nreturn array(\n";
		foreach(self::$index as $key=>$value) $out.="	'$key'=>'$value',\n";
		$out.=");\n\n?>";
		file_put_contents($index_file, $out, LOCK_EX);
	
	}
	
	//=========================================================================
 
}

spl_autoload_register('class_loader::load');

?>
