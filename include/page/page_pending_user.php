<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

?>

<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<META HTTP-EQUIV="EXPIRES" CONTENT="0">
<title>Pending account</title>
<meta http-equiv="generator" content="Kate" />
<style>
h3 {
padding: 0px;
margin: 5px;
}
</style>
</head>
<body bgcolor="#D1D1E9" text="#000000" link="#0000FF" vlink="#800080" alink="#FF0000"><table width="100%" height="100%" style="border: 10px solid #008"><tr><td align="center">

<?php

echo '<h3>Hello <span style="white-space: pre; color: #155;">'.htmlspecialchars($pending_user, ENT_QUOTES, 'UTF-8').'</span></h3>';

$rid=$rec['record_id'];

echo '<table ><tr><td align="left">';
if(!$rec['email_verified'] and $rec['email_verification_key']!='')
echo '<h3>- Your account is pending for email verification.</h3>';
if(!$rec['admin_confirmed'])
echo '<h3>- Your account is pending for admin confirmation.</h3>';
echo '</td></tr><tr><td align="center">';

if(!$rec['email_verified'] and $rec['email_verification_key']!='') {
echo '<form method="post" action="email_verification_link_request.php" name="resend_form"><input type="hidden" name="email" value="', $rec['email'], '">', '<input type="hidden" name="form1" value="1">';
echo '<br><input type="submit" value="Re-send me the activation email"><br><span style="color: #f00; font-style: italic" id="cap">&nbsp;</span></form></td></tr></table>';
}

echo '<a href="index.php">Login page</a>';

?>

</td></tr></table>
<?php
require $index_dir.'include/page/page_foot_codes.php';
?>
</body>
</html>
