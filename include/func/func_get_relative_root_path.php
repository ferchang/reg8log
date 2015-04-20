<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

function get_relative_root_path() {
	
	$str=substr($_SERVER['SCRIPT_FILENAME'], strlen($_SERVER['DOCUMENT_ROOT']));
	$str=str_replace('\\', '/', $str);

	$arr=explode('/', $str);
	unset($arr[count($arr)-1]);
	unset($arr[0]);//dont merge two unsets!
	$arr=array_values($arr);//resetting indexes
	
	$depth=0;
	while($arr) {
		$path='/'.implode('/', $arr).'/';
		$tmp=substr(ROOT, -strlen($path));
		if($path===$tmp) break;
		$depth++;
		unset($arr[count($arr)-1]);
	}
	
	$relative_root='';
	for($i=0; $i<$depth; $i++) $relative_root.='../';

	return $relative_root;

}

/*

this old algorithm (below) was working fine on local but encountered a problem on a real host!
because e.g. on local:
ROOT=D:/Program Files/EasyPHP-5.3.9/www/reg8log/
$_SERVER['SCRIPT_FILENAME']=D:/Program Files/EasyPHP-5.3.9/www/reg8log/admin/test.php
but on my host:
ROOT=/var/www/clients/client67/web172/web/reg8log/
$_SERVER['SCRIPT_FILENAME']=/var/www/mydomain123.com/web/reg8log/admin/test.php
as u can see, on a real host, ROOT and $_SERVER['SCRIPT_FILENAME'] don't share a common prefix up to the project root directory.
so i devised a new algorithm (above) that works correctly both on local and on my host. i hope it will work on other environments without problems too.

function get_relative_root_path() {

	$relative_root='';
	$depth=substr_count($_SERVER['SCRIPT_FILENAME'], '/', strlen(ROOT));
	for($i=0; $i<$depth; $i++) $relative_root.='../';

	return $relative_root;

}

*/

?>
