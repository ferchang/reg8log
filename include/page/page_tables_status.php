<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

$color1='#aaa';
$color2='#ccc';

?>

<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<META HTTP-EQUIV="EXPIRES" CONTENT="0">
<script src="../js/common.js"></script>
<link href="../css/list.css" media="screen" rel="stylesheet" type="text/css" />
<title>Tables status</title>
<style>
</style>
<script>

var tmp;
function highlight(row) {
	tmp=row.style.background;
	row.style.background="#fff";
}

function unhighlight(row) {
	row.style.background=tmp;
}

function delete_click(id, checked) {
	if(!checked) normal(id);
	else red(id);
}

function green(id) {
	tmp=document.getElementById(id).style.background="green";
}

function red(id) {
	tmp=document.getElementById(id).style.background="red";
}

function normal(id) {
	if(id%2) tmp=document.getElementById(id).style.background='<?php echo $color1 ?>';
	else tmp=document.getElementById(id).style.background='<?php echo $color2 ?>';
}

</script>
</head>
<body bgcolor="#D1D1E9" text="#000000" link="#0000FF" vlink="#800080" alink="#FF0000">
<table width="100%" height="100%"><tr><td align="center">
<table bgcolor="#7587b0" border>
<?php

echo '<tr style="background: brown; color: #fff"><th>&nbsp;</th>';

echo '<th>';
echo "<a class='header' href='?sort_by=table_name&sort_dir=";
if($sort_by=='table_name' and $sort_dir=='asc') echo 'desc';
else echo 'asc';
echo "'>Table name</a>";
if($sort_by=='table_name') {
	echo '&nbsp;';
	if($sort_dir=='asc') echo '<img src="../image/sort_asc.gif">';
	else echo '<img src="../image/sort_desc.gif">';
}
echo "</th>";

echo '<th>';
echo "<a class='header' href='?sort_by=num_records&sort_dir=";
if($sort_by=='num_records' and $sort_dir=='asc') echo 'desc';
else echo 'asc';
echo "'>Num records</a>";
if($sort_by=='num_records') {
	echo '&nbsp;';
	if($sort_dir=='asc') echo '<img src="../image/sort_asc.gif">';
	else echo '<img src="../image/sort_desc.gif">';
}
echo "</th>";

echo '</tr>';

foreach(glob($index_dir.'setup/sql/*.sql') as $file) {
	$tablename=basename($file, '.sql');
	$query="select count(*) as `n` from `$tablename`";
	$reg8log_db->query($query);
	$rec=$reg8log_db->fetch_row();
	$tables[$tablename]=$rec['n'];
}

if($sort_by=='table_name') ($sort_dir=='asc')? ksort($tables) : krsort($tables);
else if($sort_by=='num_records') ($sort_dir=='asc')? asort($tables) : arsort($tables);

$i=0;
$r=false;
foreach($tables as $key=>$value) {
	if(!$r) echo '<tr align="center" style="background: ', $color1,'" onmouseover="highlight(this);" onmouseout="unhighlight(this);"';
	else echo '<tr align="center" style="background: ', $color2,'" onmouseover="highlight(this);" onmouseout="unhighlight(this);"';
	$i++;
	echo ' id="', $i, '">';
	$r=!$r;
	echo "<td>$i</td>";
	echo "<td align='left' style='padding-left: 7px'>$key</td>";
	echo "<td>$value</td>";
	echo '</tr>';
}

?>
</table>
<br><a href="index.php">Admin operations</a><br><br>
<a href="../index.php">Login page</a>
</td></tr></table>
<?php
require $index_dir.'include/page/page_foot_codes.php';
?>
</body>
</html>
