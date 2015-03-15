<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

define('ROOT', str_replace('/include', '', str_replace('\\', '/', __DIR__)).'/');

require ROOT.'include/config/config_common.php';

if($debug_mode) {
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
}
else ini_set('display_errors', '0');

$req_time=time();

ob_start();

if(!empty($_SERVER['HTTPS']) and $_SERVER['HTTPS']!=='off' || $_SERVER['SERVER_PORT']==443) define('HTTPS', true);
else define('HTTPS', false);

require ROOT.'include/class/class_class_loader.php';

if(isset($_COOKIE['reg8log_client_sess_key'])) $client_sess_key=$_COOKIE['reg8log_client_sess_key'];
else {
	$client_sess_key=func::random_string(22);
	setcookie('reg8log_client_sess_key', $client_sess_key, 0, '/', null, HTTPS, true);
}

require ROOT.'include/code/sess/code_sess_start.php';

//----------------------------------

if($log_errors) {
	if(!$debug_mode) error_reporting($log_errors);
	//if debug_mode is on, error reporting level shouldn't be changed (it is set to E_ALL)
	ini_set('log_errors', 1);
	$error_log_file=ROOT.'file_store/error_log.txt';
	ini_set('error_log', $error_log_file);
	//ini_set('ignore_repeated_errors', 1);
	//ini_set('ignore_repeated_source', 1);
	if(file_exists($error_log_file)) {
		if(!is_writable($error_log_file)) trigger_error('reg8log: Error log file not writable!', E_USER_WARNING);
	}
	else if(!is_writable(dirname($error_log_file))) trigger_error('reg8log: Error log directory not writable!', E_USER_WARNING);
}

//----------- language ------------>

if(!$admin_emails_lang) $admin_emails_lang=$lang;

if(isset($_COOKIE['reg8log_lang']) and preg_match('/^[a-z]{2}$/', $_COOKIE['reg8log_lang'])) $lang=$_COOKIE['reg8log_lang'];

if($lang=='fa') {
	$page_dir='dir="rtl"';
	$cell_align='align="left"';
}
else {
	$page_dir='';
	$cell_align='align="right"';
}

//----------- language ------------<

ignore_user_abort(true);

if(get_magic_quotes_gpc()) {
	$_GET=array_map('stripslashes', $_GET);
	$_POST=array_map('stripslashes', $_POST);
	$_COOKIE=array_map('stripslashes', $_COOKIE);
}

header("X-Frame-Options: SAMEORIGIN");

if(!$db_installed) require ROOT.'include/code/code_check_db_setup_status.php';

require ROOT.'include/code/code_gather_request_entropy.php';

//---------- antixsrf_token ------------

if(!isset($_COOKIE['reg8log_antixsrf_token4post'])) {
	$antixsrf_token=func::random_string(22);
	setcookie('reg8log_antixsrf_token4post', $antixsrf_token, 0, '/', null, HTTPS, true);
	$_COOKIE['reg8log_antixsrf_token4post']=$antixsrf_token;
}
else $_COOKIE['reg8log_antixsrf_token4post']=htmlspecialchars($_COOKIE['reg8log_antixsrf_token4post'], ENT_QUOTES, 'UTF-8');

if(!isset($_COOKIE['reg8log_antixsrf_token4get'])) {
	$antixsrf_token=func::random_string(22);
	setcookie('reg8log_antixsrf_token4get', $antixsrf_token, 0, '/', null, HTTPS, true);
	$_COOKIE['reg8log_antixsrf_token4get']=$antixsrf_token;
}
else $_COOKIE['reg8log_antixsrf_token4get']=htmlspecialchars($_COOKIE['reg8log_antixsrf_token4get'], ENT_QUOTES, 'UTF-8');

//---------- antixsrf_token ------------

if(!$db_installed) {
	if(isset($setup_page) or isset($change_lang_page)) return;
	require ROOT.'include/page/page_not_setup.php';
	exit;
}

func::load_function_definition('shutdown_session');

register_shutdown_function('shutdown_session');

?>