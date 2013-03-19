<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

function tr($str, $add_direction=false, $force_lang='') {

	global $lang;
	global $index_dir;
	global $parent_page;

	static $fa_translations;
	static $en_translations;

	$orig_str=$str;
	$str=strtolower($str);
	
	if(!$force_lang) $tmp_lang=$lang;
	else $tmp_lang=$force_lang;
	
	if($tmp_lang=='en') {
		if(!$en_translations) $en_translations=require $index_dir."include/lang/lang_en.php";
		foreach($en_translations as $key=>$value) if(strtolower($key)==$str) return $en_translations[$key];
	}
	else {
		if(!$fa_translations) $fa_translations=require $index_dir."include/lang/lang_fa.php";
		foreach($fa_translations as $key=>$value) if(strtolower($key)==$str) {
			if($add_direction) return '<div style="display: inline-block" dir="rtl">'.$fa_translations[$key].'</div>';
			else return $fa_translations[$key];
		}
	}
	
	return $orig_str;

}

?>
