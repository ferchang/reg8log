<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");



require_once $index_dir.'include/func/func_utf8.php';

$fields=array('password'=>$fields['password'], 'email'=>$fields['email']);

foreach($fields as $field_name=>$specs) {//validate post data

if($field_name=='password' and strpos($_POST[$field_name], "hashed-$site_salt")===0) continue;

$min_length=$specs['minlength'];
$max_length=$specs['maxlength'];
$re=$specs['php_re'];

if(!isset($_POST[$field_name]))
$err_msgs[]="No $field_name field exist!";
else {//field exists
$field_value=$_POST[$field_name];

if(utf8_strlen($field_value)<$min_length)
$err_msgs[]="$field_name is shorter than $min_length characters!";//Shorter than minimum length
else if(utf8_strlen($field_value)>$max_length)
$err_msgs[]="$field_name is longer than $max_length characters!";//Longer than maximum length
else if($re and $field_value and !preg_match($re, $field_value))
$err_msgs[]="$field_name is invalid!";
else if($field_name=='password' and $field_value!=$_POST['repass']) $err_msgs[]="password fields aren't match!";
else if($field_name=='email' and $field_value!=$_POST['reemail']) $err_msgs[]="email fields aren't match!";

}//field exists

}//validate post data

?>