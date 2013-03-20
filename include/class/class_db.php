<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

class reg8log_db {

var  $err_msg='';
var  $link=null;
var $result=null;
var $auto_abort=false;

//=======================================
function reg8log_db($host='', $user='', $pass='', $reg8log_db_name='', $auto_abort=null)
{

if(!is_null($auto_abort)) $this->auto_abort=$auto_abort;

$this->link= @ mysql_connect($host, $user, $pass);
if(!$this->link) {
$this->error();
return;
}

if($reg8log_db_name) $this->select($reg8log_db_name);

}
//=======================================
function select($reg8log_db_name)
{
$this->err_msg='';
if( @ mysql_select_db($reg8log_db_name)) return true;
$this->error();
return false;
}
//=======================================
function close()
{
$this->err_msg='';
if(is_resource($this->link)) {
if( @ mysql_close($this->link)) return true;
$this->error();
return false;
}
else {
$this->error('Apparently there is no MySQL connection resource to close');
return false;
}

}
//=======================================
function free_result() {
if(is_resource($this->result))if(! @ mysql_free_result($this->result)) {
$this->error();
return false;
}
else return true;

$this->error('No query result exists');
return false;
}
//=======================================
function destruct() {
if(is_resource($this->result)) if(! @ mysql_free_result($this->result)) {
$this->error();
return false;
}

if(is_resource($this->link)) if($this->close()) return true;
else {
$this->error();
return false;
}

$this->error('Apparently there is no MySQL connection resource to close');
return false;
}
//=======================================
function error($err_msg='')
{

	global $index_dir;
	global $debug_mode;
	global $parent_page;

	$this->err_msg=get_class($this).': '.(($err_msg)? $err_msg : mysql_error());

	if($this->auto_abort) {
		$failure_msg=($debug_mode)? $this->err_msg : "Database error";
		global $page_dir;
		require $index_dir.'include/page/page_failure.php';
		exit;
	}

}
//========= query related =========

function result_num($query=null)
{
$this->err_msg='';
if(!is_null($query) and !$this->query($query)) return false;
if(is_resource($this->result)) return mysql_num_rows($this->result);
$this->error('No query result exists');
return false;
}
//=======================================
function count_star($query=null) //for count(*) queries only
{
	$this->err_msg='';
	if(!is_null($query) and !$this->query($query)) return false;
	if(!is_resource($this->result)) {
		$this->error('No valid query result');
		return false;
	}
	$rec=$this->fetch_row(MYSQL_NUM);
	return $rec[0];
}

//=======================================
function quote_smart($value, $identifier=false)
{

if(!is_numeric($value)) {
//if(get_magic_quotes_gpc()) $value = stripslashes($value);
if(!$identifier) return "'" .mysql_real_escape_string($value) . "'";
else if(strpos($value, '`')===false) return '`' .$value . '`';
else {
$this->error("Value contains invalid character (backtick - '`') for identifiers");
return false;
}
}
else return $value;

}
//=======================================
function query($query)
{
	$this->err_msg='';
	$this->result= @ mysql_query($query, $this->link);
	if($this->result) return $this->result;
	$this->error();
	return false;
}
//=======================================
function fetch_row($type=MYSQL_ASSOC)
{

$this->err_msg='';
if(is_resource($this->result)) return mysql_fetch_array($this->result, $type);
$this->error('No query result exists');
return false;

}
//=======================================
function field_name($no=null)
{

$this->err_msg='';
if(is_resource($this->result)) {//result
$num=mysql_num_fields($this->result);
if(!is_null($no)) {//$no
if(!is_int($no)) {
$this->error("Invalid no value '$no'");
return false;
}
if($no>=0 and $no<$num) return mysql_field_name($this->result, $no);
$this->error('Field number out of range');
return false;
}//$no
else {
for($i=0; $i<$num; $i++) $arr[]=mysql_field_name($this->result, $i);
return $arr;
}
}//result
$this->error('No query result exists');
return false;

}

}

?>
