<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(!isset($index_dir)) $index_dir='';

$parent_page=true;

$color1='#aaa';
$color2='#ccc';

if($page*$per_page>$total) $less=($page*$per_page)-$total;
else $less=0;
$first=($page-1)*$per_page+1;
$last=($page*$per_page-$less);
$num=$last-$first+1;

?>

<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<META HTTP-EQUIV="EXPIRES" CONTENT="0">
<title>Banned users</title>
<style>
.page {
	font-weight: bold;
	color: #fff;
	padding: 3px;
	border: 1px solid #fff;
}
</style>
</head>
<body bgcolor="#7587b0">
<center>
<form action="" method="post" name="banned_users_form">
<?php

echo '<input type="hidden" name="antixsrf_token" value="';
echo $_COOKIE['reg8log_antixsrf_token'];
echo '">';

require $index_dir.'include/code/code_generate_form_id.php';

echo 'Records ', $first, ' - ', $last, ' of ', $total;
echo '<table border cellpadding="3">';

if(isset($err_msgs)) {
	echo '<tr align="center"><td colspan="2"  style="border: solid thin yellow; font-style: italic;"><span style="color: #800">Errors:</span><br />';
	foreach($err_msgs as $err_msg) {
		$err_msg[0]=strtoupper($err_msg[0]);
		echo "<span style=\"color: yellow\" >$err_msg</span><br />";
	}
	echo '</td></tr>';
}

require_once $index_dir.'include/func/func_duration2msg.php';

echo '<tr style="background: brown; color: #fff"><th></th><th>Username</th><th>uid</th><th>Gender</th><th>Email</th><th>Member for</th><th>Ban until</th></tr>';

$i=0;
$r=false;
while($rec=$reg8log_db->fetch_row()) {
	if(!$r) echo '<tr align="center" style="background: ', $color1,'">';
	else echo '<tr align="center" style="background: ', $color2,'">';
	$i++;
	$r=!$r;
	$row=($page-1)*$per_page+$i;
	echo '<td>', $row, '</td>';
	echo '<td>', htmlspecialchars($rec['username'], ENT_QUOTES, 'UTF-8'), '</td>';
	echo '<td>', $rec['uid'], '</td>';
	echo '<td>', $rec['gender'], '</td>';
	echo '<td>', $rec['email'], '</td>';
	echo '<td>', duration2friendly_str(time()-$rec['timestamp'], 2), '</td>';
	echo '<td>';
	if($rec['banned']==1) echo 'Not specified';
	else echo duration2friendly_str($rec['banned']-time(), 2), ' later';
	echo '</td>';
	echo '</tr>';
}
echo '</table>';

if($total>$per_page) {
	echo '<br>';
	for($i=1; $i<=ceil($total/$per_page); $i++) {
		echo '<a class="page" href="admin-banned_users.php?per_page=', $per_page, '&page=', $i,'" style="';
		if($page==$i) echo ' background: #fff; color: #000; border: 1px solid #000';
		echo '">', $i, '</a>&nbsp;&nbsp;';
	}
	echo '<br>';
}

if($total>$per_pages[0]) {
	if($total<=$per_page) echo '<br>';
	echo '<br>Records per page: <select name="per_page" onchange="document.banned_users_form.change_per_page.click()">';
	foreach($per_pages as $value) {
		if($value!=$per_page) echo "<option>$value</option>";
		else echo "<option selected>$value</option>";
	}
	echo '</select>&nbsp;<input type="submit" value="Show" name="change_per_page" style="display: visible">';
	echo  '<script>
	document.banned_users_form.change_per_page.style.display="none";
	</script>';
}

?>
</form>
<a href="admin_operations.php">Admin operations</a><br><br>
<a href="../index.php">Login page</a>
</center>
</body>
</html>
