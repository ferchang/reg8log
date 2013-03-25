<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

$tmp18='[\x{067E}\x{0622}\x{0627}\x{0628}\x{062A}-\x{063A}\x{0641}-\x{064A}\x{0698}\x{06A9}\x{06AF}\x{06C1}\x{06CC}]|[a-zA-Z0-9]';
$username_php_re="/^($tmp18)($tmp18|\\s($tmp18))*$/u";
$tmp18=preg_replace('/\x5cx\{([0-9A-F]{4})\}/', '\u$1', $username_php_re);
$username_js_re=substr($tmp18, 0, -1);

$fields=array(
	//captcha must be the first member of the $fields array
	'captcha'=>array(
		'minlength'=>5,
		'maxlength'=>5,
		'php_re'=>'/^[2345679ACEFGHJKLMNPRSTUVWXYZ]*$/i', //a string: server side regular expression / an empty string or false: no server side regular expression validation
		'js_re'=>true, //a string: client side regular expression / true: use php_re for client side too / false: no client side regular expression validation
		'unique'=>false, //whether field value must be unique within database
		'value'=>false,
		'client_validate'=>true //whether to validate this field specs on the client side too
	),
	'username'=>array(
		'minlength'=>1,
		'maxlength'=>30,
		'php_re'=>$username_php_re,
		'js_re'=>$username_js_re,
		'unique'=>true,
		'value'=>'',
		'client_validate'=>true
	),
	'password'=>array(
		'minlength'=>6,
		'maxlength'=>128,
		'php_re'=>'',
		'js_re'=>false,
		'unique'=>false,
		'value'=>false,
		'client_validate'=>true
	),
	'email'=>array(
		'minlength'=>6,
		'maxlength'=>60,
		'php_re'=>'/^[a-z0-9_\-+\.]+@([a-z0-9\-+]+\.)+[a-z]{2,5}$/i',
		'js_re'=>true,
		'unique'=>true,
		'value'=>'',
		'client_validate'=>true
	),
	'gender'=>array(
		'minlength'=>1,
		'maxlength'=>1,
		'php_re'=>'/^[mfn]$/i',
		'js_re'=>false,
		'unique'=>false,
		'value'=>'',
		'client_validate'=>false
	)
);

?>
