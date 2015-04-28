<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$query="select * from `accounts` where `username`=".$reg8log_db->quote_smart($identified_user).' limit 1';

$reg8log_db->query($query);

$rec=$reg8log_db->fetch_row();

?>

<html <?php echo PAGE_DIR; ?>>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<META HTTP-EQUIV="EXPIRES" CONTENT="0">
<link href="../css/list.css" media="screen" rel="stylesheet" type="text/css" />
<title><?php echo func::tr('Account info'); ?></title>
</head>
<body bgcolor="#D1D1E9" <?php echo PAGE_DIR; ?>>
<center><br><br>
<table border cellpadding="3">
<tr style="background: brown; color: #fff">
<?php
echo '<th>', func::tr('Username'), '</th>';
echo '<th>', func::tr('Gender'), '</th>';
echo '<th>', func::tr('Email'), '</th>';
echo '<th>',func::tr('Account creation'), '</th>';
if(config::get('log_last_login')) echo '<th>', func::tr('Last login'), '</th>';
if(config::get('log_last_logout')) echo '<th>', func::tr('Last logout'), '</th>';
echo '<th>', func::tr('Banned'), '</th>';

echo '</tr><tr align="center" style="background: #35a; color: #fff">';

echo '<td>', htmlspecialchars($rec['username'], ENT_QUOTES, 'UTF-8'), '</td>';

echo '<td>';
if($rec['gender']==='n') echo '?';
else if($rec['gender']==='m') echo func::tr('Male');
else echo func::tr('Female');
echo '</td>';

echo '<td>', $rec['email'], '</td>';

echo '<td>', func::duration2friendly_str($req_time-$rec['timestamp'], 2), func::tr(' ago'), '</td>';

if(config::get('log_last_login')) {
	if($rec['last_login']) echo '<td>', func::duration2friendly_str($req_time-$rec['last_login'], 2), func::tr(' ago'), '</td>';
	else echo '<td>', func::tr('N/A'), '</td>';
}

if(config::get('log_last_logout')) {
	if($rec['last_logout']) echo '<td>', func::duration2friendly_str($req_time-$rec['last_logout'], 2), func::tr(' ago'), '</td>';
	else echo '<td>', func::tr('N/A'), '</td>';
}

if($rec['banned']==1 or $rec['banned']>$req_time) echo '<td>', func::tr('Yes'), '</td>';
else echo '<td>', func::tr('No'), '</td>';

echo '</tr></table>';

?>
<br><a href="user_options.php"><?php echo func::tr('User options'); ?></a><br><br>
<a href="../index.php"><?php echo func::tr('Login page'); ?></a>
</center>
<?php
require ROOT.'include/page/page_foot_codes.php';
?>
</body>
</html>
