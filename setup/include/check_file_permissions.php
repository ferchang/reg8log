<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$tmp10=array();

$warning_tr=func::tr('Warning');

//--------------------

$error_log_file=ROOT.'file_store/error_log.txt'

if(file_exists($error_log_file)) {
		if(!is_writable($error_log_file)) $tmp10[]="$warning_tr: ".func::tr('Error log file not writable!');
}
else if(!is_writable(dirname($error_log_file))) $tmp10[]="$warning_tr: ".func::tr('Error log directory not writable!');

//--------------------

$config_cache_file=ROOT.'file_store/config_cache.txt';

if(file_exists($config_cache_file)) {
		if(!is_readable($config_cache_file)) $tmp10[]="$warning_tr: ".func::tr('Config cache file not readable!');
		if(!is_writable($config_cache_file)) $tmp10[]="$warning_tr: ".func::tr('Config cache file not writable!');
}
else if(!is_writable(dirname($config_cache_file))) $tmp10[]="$warning_tr: ".func::tr('Config cache directory not writable!');

//--------------------

if(!empty($tmp10)) echo '<hr style="width: 250px"><div style="color: orange; background: #000; padding: 3px; ">', implode('<br>', $tmp10), '</div>';

?>