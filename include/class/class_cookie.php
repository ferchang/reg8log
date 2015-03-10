<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

class hm_cookie
{

var $name=null;
var $value_seperator=null;
var $values=null;
var $path='/';
var $domain=null;
var $secure=false;
var $httponly=true;
var $err_msg='';
var $long_age=31536000; //3153600 seconds: one year (365 days)

//=======================================
function hm_cookie($name=null, $value_seperator=null)
{
$this->name=$name;
$this->value_seperator=$value_seperator;
}
//=======================================
function error($err_msg='No error message specified')
{
$this->err_msg=get_class($this).": $err_msg";
}
//=======================================
function set($name=null, $values=array(), $value_seperator=null, $age='session', $is_abs_time=false)
{

global $req_time;

$this->err_msg='';

if(is_null($name)) $name=$this->name;
if(is_null($value_seperator)) $value_seperator=$this->value_seperator;

if(!$name and $name!='0') {
$this->error('No cookie name specified');
return false;
}

$val='';
if(is_array($values)) {
	if(is_null($value_seperator)) {
		$this->error('No value seperator specified');
		return false;
	}
	$val=implode($value_seperator, $values);
}
else $val=$values;

switch("$age") {
	case 'permanent':
		$expire=$req_time+$this->long_age;
	break;
	case 'session':
		$expire=0;
	break;
	default:
		$expire=(($is_abs_time)? $age : (($age)? $age+$req_time : 0));
	break;
}

if(setcookie($name, $val, $expire, $this->path, $this->domain, $this->secure, $this->httponly)) return true;

$this->error('Problem with setting cookie');
return false;
}//end set
//=======================================
function get($name=null, $value_seperator=null)
{

$this->err_msg='';

if(is_null($name)) $name=$this->name;
if(!$name and $name!=='0') {
$this->error('No cookie name specified');
return false;
}

if($name===$this->name and is_null($value_seperator)) $value_seperator=$this->value_seperator;
$this->value_seperator=$value_seperator;

if(!isset($_COOKIE[$name])) {
$this->error("Cookie '$name' dos not exist");
return false;
}

if($value_seperator or $value_seperator==='0') $this->values=explode($value_seperator, $_COOKIE[$name]);
else $this->values=$_COOKIE[$name];

return $this->values;
}//end get
//=======================================
function erase($name=null)
{

$this->err_msg='';

if(is_null($name)) $name=$this->name;
if(!$name and $name!=='0') {
$this->error('No cookie name specified');
return false;
}

return setcookie($name, false, mktime(12,0,0,1, 1, 1990), $this->path, $this->domain, $this->secure, $this->httponly);
}//end erase
//=======================================
}

?>
