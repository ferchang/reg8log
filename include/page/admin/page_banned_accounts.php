<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

require ROOT.'include/page/admin/page_pagination_initials.php';

?>

<html <?php echo $page_dir; ?>>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<META HTTP-EQUIV="EXPIRES" CONTENT="0">
<link href="../css/list.css" media="screen" rel="stylesheet" type="text/css" />
<title><?php echo func::tr('Banned users'); ?></title>
<style>
</style>
<script>
<?php
require ROOT.'include/page/admin/page_common_list_funcs-js.php';
?>
</script>
</head>
<body bgcolor="#7587b0" <?php echo $page_dir; ?>>
<center>
<form action="" method="post" name="banned_users_form">
<?php

echo '<input type="hidden" name="antixsrf_token" value="';
echo $_SESSION['reg8log']['antixsrf_token4post'];
echo '">';

require ROOT.'include/code/code_generate_form_id.php';

echo func::tr('Records '), $first, func::tr(' - '), $last, func::tr(' of '), $total;
echo '<table border cellpadding="3">';

echo '<tr style="background: brown; color: #fff"><th></th>';

echo '<th>';
echo "<a class='header' href='?per_page=$per_page&page=$page&sort_by=username&sort_dir=";
if($sort_by=='username' and $sort_dir=='asc') echo 'desc';
else echo 'asc';
echo "'>", func::tr('Username'), "</a>";
if($sort_by=='username') {
	echo '&nbsp;';
	if($sort_dir=='asc') echo '<img src="../image/sort_asc.gif">';
	else echo '<img src="../image/sort_desc.gif">';
}
echo "</th>";

echo '<th>';
echo "<a class='header' href='?per_page=$per_page&page=$page&sort_by=uid&sort_dir=";
if($sort_by=='uid' and $sort_dir=='asc') echo 'desc';
else echo 'asc';
echo "'>", func::tr('Uid'), "</a>";
if($sort_by=='uid') {
	echo '&nbsp;';
	if($sort_dir=='asc') echo '<img src="../image/sort_asc.gif">';
	else echo '<img src="../image/sort_desc.gif">';
}
echo "</th>";

echo '<th>';
echo "<a class='header' href='?per_page=$per_page&page=$page&sort_by=gender&sort_dir=";
if($sort_by=='gender' and $sort_dir=='asc') echo 'desc';
else echo 'asc';
echo "'>", func::tr('Gender'), "</a>";
if($sort_by=='gender') {
	echo '&nbsp;';
	if($sort_dir=='asc') echo '<img src="../image/sort_asc.gif">';
	else echo '<img src="../image/sort_desc.gif">';
}
echo "</th>";

echo '<th>';
echo "<a class='header' href='?per_page=$per_page&page=$page&sort_by=email&sort_dir=";
if($sort_by=='email' and $sort_dir=='asc') echo 'desc';
else echo 'asc';
echo "'>", func::tr('Email'), "</a>";
if($sort_by=='email') {
	echo '&nbsp;';
	if($sort_dir=='asc') echo '<img src="../image/sort_asc.gif">';
	else echo '<img src="../image/sort_desc.gif">';
}
echo "</th>";

echo '<th>';
echo "<a class='header' href='?per_page=$per_page&page=$page&sort_by=timestamp&sort_dir=";
if($sort_by=='timestamp' and $sort_dir=='asc') echo 'desc';
else echo 'asc';
echo "'>", func::tr('Member for'), "</a>";
if($sort_by=='timestamp') {
	echo '&nbsp;';
	if($sort_dir=='asc') echo '<img src="../image/sort_asc.gif">';
	else echo '<img src="../image/sort_desc.gif">';
}
echo "</th>";

echo '<th>';
echo "<a class='header' href='?per_page=$per_page&page=$page&sort_by=banned&sort_dir=";
if($sort_by=='banned' and $sort_dir=='asc') echo 'desc';
else echo 'asc';
echo "'>", func::tr('Ban until'), "</a>";
if($sort_by=='banned') {
	echo '&nbsp;';
	if($sort_dir=='asc') echo '<img src="../image/sort_asc.gif">';
	else echo '<img src="../image/sort_desc.gif">';
}
echo "</th>";

echo '<th>';
echo "<a class='header' href='?per_page=$per_page&page=$page&sort_by=reason&sort_dir=";
if($sort_by=='reason' and $sort_dir=='asc') echo 'desc';
else echo 'asc';
echo "'>", func::tr('Ban reason'), "</a>";
if($sort_by=='reason') {
	echo '&nbsp;';
	if($sort_dir=='asc') echo '<img src="../image/sort_asc.gif">';
	else echo '<img src="../image/sort_desc.gif">';
}
echo "</th>";

echo '</tr>';

$i=0;
$r=false;
while($rec=$reg8log_db->fetch_row()) {
	if(!$r) echo '<tr align="center" style="background: ', $color1,'" onmouseover="highlight(this);" onmouseout="unhighlight(this);"';
	else echo '<tr align="center" style="background: ', $color2,'" onmouseover="highlight(this);" onmouseout="unhighlight(this);"';
	$i++;
	echo ' id="row', $i, '">';
	$r=!$r;
	$row=($page-1)*$per_page+$i;
	echo '<td>', $row, '</td>';
	echo '<td>', htmlspecialchars($rec['username'], ENT_QUOTES, 'UTF-8'), '</td>';
	echo '<td>', $rec['uid'], '</td>';
	echo '<td>';
	if($rec['gender']=='n') echo '?';
	else if($rec['gender']=='m') echo func::tr('Male');
	else echo func::tr('Female');
	echo '</td>';
	echo '<td>', $rec['email'], '</td>';
	echo '<td>', func::duration2friendly_str($req_time-$rec['timestamp'], 2), '</td>';
	echo '<td>';
	if($rec['banned']==1) echo func::tr('Unlimited');
	else echo func::duration2friendly_str($rec['banned']-$req_time, 2), func::tr(' later');
	echo '</td>';
	if(is_null($rec['reason'])) echo '<td title="', func::tr('No corresponding ban_info record found'), '"><span style="color: yellow">?</span>';
	else if($rec['reason']!=='') echo '<td>', $rec['reason'];
	else echo '<td title="', func::tr('No ban reason specified'), '">&nbsp;';
	echo '</td>';
	echo '</tr>';
}
echo '</table>';

require ROOT.'include/page/admin/page_gen_paginated_page_links.php';

$form_name='banned_users_form';
require ROOT.'include/page/admin/page_per_pages_select.php';

?>
</form>
<a href="index.php"><?php echo func::tr('Admin operations'); ?></a><br><br>
<a href="../index.php"><?php echo func::tr('Login page'); ?></a>
</center>
<?php
require ROOT.'include/page/page_foot_codes.php';
?>
</body>
</html>

