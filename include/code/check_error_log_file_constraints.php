<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(mt_rand(1, 5)!==1) {
	$size=filesize(ERROR_LOG_FILE);
	if($size>$error_log_file_max_size+($error_log_file_max_size/20)) {
		$logs=file_get_contents(ERROR_LOG_FILE);
		$logs=substr($logs, $size-$error_log_file_max_size);
		file_put_contents(ERROR_LOG_FILE, ERROR_LOG_HEADER_STR.$logs, LOCK_EX);
	}
}
else {
	$logs=file_get_contents(ERROR_LOG_FILE);
	if(substr($logs, 0, strlen(ERROR_LOG_HEADER_STR))!==ERROR_LOG_HEADER_STR) file_put_contents(ERROR_LOG_FILE, ERROR_LOG_HEADER_STR.$logs, LOCK_EX);
}

/* probably these codes seem not much optimised. u know, and i know too!
u would probably suggest using more fine tuned file system operations instead of using things like $logs=file_get_contents(ERROR_LOG_FILE) that load the entire file contents into memory at once. i know! and believe that i can!
i more or less believe in 'performance is not a problem until it is a problem!'
so more legible, easy and fast coding is preferred in general. it is obvious that it is also of great help to having less bugs and security holes.
also consider that these codes aren't run in every request (u saw mt_rands here and in common.php! there are a few other optimisation details too).
so indeed i think i have implemented some simple but significant/sufficient optimization for it! also i think a really big error log file isn't a normal situation anyway.
*/

?>