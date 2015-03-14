<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

class class_loader {

	private static $index=array();
	private static $index_file='file_store/class_index.php';
	
	//=========================================================================
 
    public static function load($x__class_name) {
		
		if(empty($index)) {
			$x__index_file=ROOT.class_loader::$index_file;
			if(isset($_SESSION['reg8log']['class_index'])) class_loader::$index=$_SESSION['reg8log']['class_index'];
			else if(!isset($_SESSION['reg8log']['cant_use_class_index_file']) and file_exists($x__index_file)) {
					//echo "reading index from file...\n";
					if(is_readable($x__index_file)) class_loader::$index=$_SESSION['reg8log']['class_index']=include $x__index_file;
					else class_loader::report_index_file_error('Class index file not readable');
			}
		}
		
		if(empty(class_loader::$index) or !array_key_exists($x__class_name, class_loader::$index)) class_loader::update_index();
		
		if(!array_key_exists($x__class_name, class_loader::$index)) trigger_error("reg8log: class class_loader: error: '$x__class_name' class file not found!", E_USER_ERROR);
		
		include_once ROOT.'include/class/'.class_loader::$index[$x__class_name];
		//don't use require_once, because if a class's file is renamed it will block our class autoloader mechanism. (no index auto-updating will occur because the code execution will stop at this point)
		
		if(!class_exists($x__class_name)) {//class can be deleted or moved into a different file
			class_loader::update_index();
			if(!array_key_exists($x__class_name, class_loader::$index)) trigger_error("reg8log: class class_loader: error: '$x__class_name' class file not found! (2)", E_USER_ERROR);
			require_once ROOT.'include/class/'.class_loader::$index[$x__class_name];
		}
		
		foreach(get_defined_vars() as $name => $value) $GLOBALS[$name] = &$$name;

	}
	
	//=========================================================================
	
	private static function update_index() {
		
		//echo "updating index...\n";
		
		class_loader::$index=array();
		
		foreach(glob(ROOT.'include/class/class_*.php') as $filename) {
			$contents=file_get_contents($filename);
			preg_match_all('#^\h*class\h*([0-9a-z_]*)\h*\{#im', $contents, $matches);
			foreach($matches[1] as $class_name) class_loader::$index[$class_name]=basename($filename);
		}
		
		$_SESSION['reg8log']['class_index']=class_loader::$index;
		
		if(isset($_SESSION['reg8log']['cant_use_class_index_file'])) return;
		
		$index_file=ROOT.class_loader::$index_file;
		if(file_exists($index_file)) {
			if(!is_writable($index_file)) {
				class_loader::report_index_file_error('Class index file not writable');
				return;
			}
		}
		else if(!is_writable(dirname($index_file))) {
			class_loader::report_index_file_error('Class index directory not writable');
			return;
		}
		
		$out="<?php\nreturn array(\n";
		foreach(class_loader::$index as $key=>$value) $out.="	'$key'=>'$value',\n";
		$out.=");\n?>";
		file_put_contents($index_file, $out);
	
	}
	
	//=========================================================================
	
	private static function report_index_file_error($str) {
		$_SESSION['reg8log']['cant_use_class_index_file']=true;
		trigger_error("reg8log: class class_loader: $str!", E_USER_NOTICE);
	}
	
	//=========================================================================
 
}

spl_autoload_register('class_loader::load');

?>
