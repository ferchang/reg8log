<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

class config {

	private $vars=array();
	
	//=========================================================================
	
	public function __construct() {
		
		foreach(glob(ROOT.'include/config/config_*.php') as $filename) require $filename;
		unset($filename, $tmp18, $username_php_re, $username_js_re);
		foreach(get_defined_vars() as $name => $value) $this->vars[$name] = $value;
		//echo '<pre>';
		//print_r($vars);
		//exit;
		
	}
	
	//=========================================================================
 
    public function __get($name) {

		if(isset($this->vars[$name])) return $this->vars[$name];
		else trigger_error("reg8log: class config: error: '$name' config variable not found!", E_USER_ERROR);

	}
	
	//=========================================================================

}

?>
