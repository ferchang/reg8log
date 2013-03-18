<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

function tr($str, $add_direction=false) {

	global $lang;
	global $index_dir;
	global $parent_page;

	static $translations;
	static $eng_translations;

	$orig_str=$str;
	$str=strtolower($str);
	
	if(!$translations) $translations=require $index_dir."include/lang/lang_{$lang}.php";

	foreach($translations as $key=>$value) if(strtolower($key)==$str) {
		if($add_direction and $lang=='fa') return '<div style="display: inline-block" dir="rtl">'.$translations[$key].'</div>';
		else return $translations[$key];
	}
	
	if($lang!='en') {
		if(!$eng_translations) $eng_translations=require $index_dir.'include/lang/lang_en.php';
		foreach($eng_translations as $key=>$value) if(strtolower($key)==$str) return $eng_translations[$key];
	}
	
	return $orig_str;

}

?>
