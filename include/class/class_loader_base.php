<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

class loader_base {

	//=========================================================================
	
	protected static function is_file_accessible($file, $op) {
		
		$file_name=basename($file);
		
		switch($op) {
			case 'read':
				if(!file_exists($file)) return false;
				if(!is_readable($file)) {
					self::report_file_access_error("$file_name not readable");
					return false;
				}
			break;
			case 'write':
				if(file_exists($file)) {
					if(!is_writable($file)) {
						self::report_file_access_error("$file_name not writable");
						return false;
					}
				}
				else if(!is_writable(dirname($file))) {
					$dir_name=basename(dirname($file));
					self::report_file_access_error("$dir_name directory not writable");
					return false;
				}
			break;
		}
		
		return true;
		
	}
	
	//=========================================================================
	
	protected static function report_file_access_error($str) {
		$called_class=get_called_class();
		trigger_error('reg8log: class '.$called_class.": $str!", E_USER_NOTICE);
		$tmp="class_{$called_class}_file_access_error";
		$_SESSION['reg8log'][$tmp]=true;
	}

	//=========================================================================
	
	protected static function debug_msg($str) {
		if($GLOBALS['debug_mode'] and !defined('CAPTCHA_IMG') and !defined('AJAX')) echo "<span dir=ltr>$str</span>";
	}
	
	//=========================================================================

}

?>
