<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
define('CAN_INCLUDE', true);



require 'include/common.php';

require ROOT.'include/code/code_encoding8anticache_headers.php';

require ROOT.'include/config/config_register.php';

if(!isset($_GET['rid'], $_GET['key'])) exit('<h3 align="center">Error: rid and/or key parameter is not set!</h3>');

if($_GET['rid']==='' or $_GET['key']==='') exit('<h3 align="center">Error: rid and/or key parameter is empty!</h3>');

if(isset($_POST['proceed'])) {

require ROOT.'include/code/code_prevent_repost.php';

require ROOT.'include/code/code_prevent_xsrf.php';

$home='<br /><a href="index.php">'.func::tr('Login page').'</a>';

require_once ROOT.'include/code/code_db_object.php';

$rid=$reg8log_db->quote_smart($_GET['rid']);
$key=$reg8log_db->quote_smart($_GET['key']);

require_once ROOT.'include/code/code_fetch_site_vars.php';

//$lock_name='reg8log--register--'.$site_key;
//$reg8log_db->query("select get_lock('$lock_name', -1)");

$query="select * from `email_change` where `record_id`=$rid and `email_verification_key`=$key limit 1";

if(!$reg8log_db->result_num($query)) my_exit('<center><h3>'.func::tr('no such email verification record2').'.<br>...</h3>'."$home</center>");

$rec=$reg8log_db->fetch_row();

if($change_email_verification_time==0) $verification_time=$email_verification_time;
else $verification_time=$change_email_verification_time;

$expired=$req_time-$verification_time;

if($rec['timestamp']<$expired) my_exit('<center><h3>'.func::tr('Out of email verification time msg2').'</h3>'."$home</center>");

$_username=$reg8log_db->quote_smart($rec['username']);

$query="update `accounts` set `email`='{$rec['email']}' where `username`=$_username limit 1";

$reg8log_db->query($query);

$query="delete from `email_change` where `username`=$_username limit 1";

$reg8log_db->query($query);

$success_msg='<h3>'.func::tr('Your email changed successfully').'.</h3>';
$no_specialchars=true;
require ROOT.'include/code/code_set_submitted_forms_cookie.php';
require ROOT.'include/page/page_success.php';

exit;

}

?>

<html <?php echo $page_dir; ?>>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<META HTTP-EQUIV="EXPIRES" CONTENT="0">
<title><?php echo func::tr('Email verification'); ?></title>
</head>
<body bgcolor="#7587b0" <?php echo $page_dir; ?>>
<table width="100%" height="100%">
<tr><td align="center">
<form method="post" action="">
<h3 style="margin: 5px"><?php echo func::tr('To complete your email change, click on the button below'); ?>:</h3>
<?php

echo '<input type="hidden" name="antixsrf_token" value="';
echo $_SESSION['reg8log']['reg8log_antixsrf_token4post'];
echo '">';

require ROOT.'include/code/code_generate_form_id.php';

?>
<br><input type="submit" value="<?php echo func::tr('Proceed'), '...'; ?>" name="proceed">
</form>
</td></tr>
</table>
<?php
require ROOT.'include/page/page_foot_codes.php';
?>
</body>
</html>
