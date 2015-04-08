<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

require ROOT.'include/page/admin/page_pagination_initials.php';

?>

<html <?php echo PAGE_DIR; ?>>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<META HTTP-EQUIV="EXPIRES" CONTENT="0">
<script src="../js/forms_common.js"></script>
<?php require ROOT.'include/code/code_validate_captcha_field-js.php'; ?>
<link href="../css/list.css" media="screen" rel="stylesheet" type="text/css" />
<title><?php echo func::tr('Tables status'); ?></title>
<style>
</style>
<script>

<?php
require ROOT.'include/page/admin/page_common_list_funcs-js.php';
?>

</script>
</head>
<body bgcolor="#D1D1E9" text="#000000" link="#0000FF" vlink="#800080" alink="#FF0000" <?php echo PAGE_DIR; ?>>
<table width="100%" height="100%"><tr><td align="center">
<table bgcolor="#7587b0" border>
<?php

echo '<tr style="background: brown; color: #fff"><th>&nbsp;</th>';

echo '<th>';
echo "<a class='header' href='?sort_by=table_name&sort_dir=";
if($sort_by==='table_name' and $sort_dir==='asc') echo 'desc';
else echo 'asc';
echo "'>", func::tr('Table name'), "</a>";
if($sort_by==='table_name') {
	echo '&nbsp;';
	if($sort_dir==='asc') echo '<img src="../image/sort_asc.gif">';
	else echo '<img src="../image/sort_desc.gif">';
}
echo "</th>";

echo '<th>';
echo "<a class='header' href='?sort_by=num_records&sort_dir=";
if($sort_by==='num_records' and $sort_dir==='asc') echo 'desc';
else echo 'asc';
echo "'>", func::tr('Num records'), "</a>";
if($sort_by==='num_records') {
	echo '&nbsp;';
	if($sort_dir==='asc') echo '<img src="../image/sort_asc.gif">';
	else echo '<img src="../image/sort_desc.gif">';
}
echo "</th>";

echo '</tr>';

foreach(glob(ROOT.'setup/sql/*.sql') as $file) {
	$tablename=basename($file, '.sql');
	$query="select count(*) as `n` from `$tablename`";
	$reg8log_db->query($query);
	$rec=$reg8log_db->fetch_row();
	$tables[$tablename]=$rec['n'];
}

if($sort_by==='table_name') ($sort_dir==='asc')? ksort($tables) : krsort($tables);
else if($sort_by==='num_records') ($sort_dir==='asc')? asort($tables) : arsort($tables);

$i=0;
$r=false;
foreach($tables as $key=>$value) {
	if(!$r) echo '<tr align="center" style="background: ', $color1,'" onmouseover="highlight(this);" onmouseout="unhighlight(this);"';
	else echo '<tr align="center" style="background: ', $color2,'" onmouseover="highlight(this);" onmouseout="unhighlight(this);"';
	$i++;
	echo ' id="row', $i, '">';
	$r=!$r;
	echo "<td>$i</td>";
	echo "<td align='left' style='padding-left: 7px'>$key</td>";
	echo "<td>$value</td>";
	echo '</tr>';
}

?>
</table>
<br><a href="index.php"><?php echo func::tr('Admin operations'); ?></a><br><br>
<a href="../index.php"><?php echo func::tr('Login page'); ?></a>
</td></tr></table>
<?php
require ROOT.'include/page/page_foot_codes.php';
?>
</body>
</html>
