<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
$parent_page=true;

$index_dir='';

require $index_dir.'include/common.php';

require $index_dir.'include/code/code_encoding8anticache_headers.php';

require $index_dir.'include/info/info_register.php';

if(!isset($_GET['rid'], $_GET['key'])) exit('<h3 align="center">Error: rid and/or key parameter is not set!</h3>');

if($_GET['rid']==='' or $_GET['key']==='') exit('<h3 align="center">Error: rid and/or key parameter is empty!</h3>');

if(isset($_POST['proceed'])) {

require $index_dir.'include/code/code_prevent_repost.php';

require $index_dir.'include/code/code_prevent_xsrf.php';

$home='<br /><a href="index.php">Login page</a>';

require_once $index_dir.'include/code/code_db_object.php';

$rid=$reg8log_db->quote_smart($_GET['rid']);
$key=$reg8log_db->quote_smart($_GET['key']);

require_once $index_dir.'include/code/code_fetch_site_vars.php';

$lock_name='reg8log--register--'.$site_key;
$reg8log_db->query("select get_lock('$lock_name', -1)");

$query="select * from `pending_accounts` where `record_id`=$rid and `email_verification_key`=$key limit 1";

if(!$reg8log_db->result_num($query)) exit('<center><h3>Error:<br>No such record found!<br>Possible reasons:<br>- Out of email verification time.<br>- Not confirmed by admin in the specified time.<br>...</h3>'."$home</center>");

$rec=$reg8log_db->fetch_row();

$expired=time()-$admin_confirmation_time;

if($rec['timestamp']<$expired) exit('<center><h3>Error:<br>Pending account is expired!<br>Reason:<br>Not confirmed by admin in the specified time.</h3>'."$home</center>");

if($rec['email_verified']) {
  echo '<center><h3>Your account\'s email verification is already done!';
  if(!$rec['admin_confirmed']) echo '<br>Your account is waiting for admin confirmation.';
  echo '</h3>'."$home</center>";
  exit;
}

$expired=time()-$email_verification_time;

if($rec['timestamp']<$expired) exit('<center><h3>Error:<br>Pending account is expired!<br>Reason:<br>Out of email verification time.</h3>'."$home</center>");

$query="update `pending_accounts` set `email_verified`=1 where `record_id`=$rid limit 1";

$reg8log_db->query($query);

if(!$rec['admin_confirmed']) {
  require_once $index_dir.'include/func/duration2friendly_str.php';
  $success_msg='<h3>Your email is verified successfully.<br>Your account is waiting for admin confirmation in '.duration2friendly_str($admin_confirmation_time, 0).' from your registeration date.</h3>';
  $no_specialchars=true;
  require $index_dir.'include/page/page_success.php';
}
else {
  require $index_dir.'include/code/code_activate_pending_account.php';
  if($login_upon_register) {
    $_username=$rec['username'];
    require $index_dir.'include/code/code_login_upon_register.php';
  }
}

require $index_dir.'include/code/code_set_submitted_forms_cookie.php';

exit;
}

?>

<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<META HTTP-EQUIV="EXPIRES" CONTENT="0">
<title>Email verification</title>
</head>
<body bgcolor="#7587b0">
<table width="100%" height="100%">
<tr><td align="center">
<form method="post" action="">
<h3 style="margin: 5px">To complete your registration, click on the button below:</h3>
<?php

echo '<input type="hidden" name="antixsrf_token" value="';
echo $_COOKIE['reg8log_antixsrf_token'];
echo '">';

require $index_dir.'include/code/code_generate_form_id.php';

?>
<br><input type="submit" value="Proceed..." name="proceed">
</form>
</td></tr>
</table>
<?php
require $index_dir.'include/page/page_foot_codes.php';
?>
</body>
</html>
