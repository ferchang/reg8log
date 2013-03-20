<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

$color1='#aaa';
$color2='#ccc';

if($page*$per_page>$total) $less=($page*$per_page)-$total;
else $less=0;
$first=($page-1)*$per_page+1;
$last=($page*$per_page-$less);
$num=$last-$first+1;

?>

<html <?php echo $page_dir; ?>>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<META HTTP-EQUIV="EXPIRES" CONTENT="0">
<link href="../css/list.css" media="screen" rel="stylesheet" type="text/css" />
<title><?php echo tr('Blocked IPs'); ?></title>
<style>
</style>
<script>

var del_all_toggle_stat=false;
var unblock_all_toggle_stat=false;

var tmp;
function highlight(row) {
tmp=row.style.background;
row.style.background="#fff";
}
function unhighlight(row) {
row.style.background=tmp;
}

function unblock_click(id, checked) {
	if(document.getElementById('del'+id) && document.getElementById('del'+id).checked) {
		if(!checked) red(id);
		else orange(id);
	}
	else {
		if(!checked) normal(id);
		else yellow(id);
	}
}

function delete_click(id, checked) {
	if(document.getElementById('unblock'+id) && document.getElementById('unblock'+id).checked) {
		if(!checked) yellow(id);
		else orange(id);
	}
	else {
		if(!checked) normal(id);
		else red(id);
	}
}

function orange(id) {
tmp=document.getElementById('row'+id).style.background="orange";
}
function green(id) {
tmp=document.getElementById('row'+id).style.background="green";
}
function red(id) {
tmp=document.getElementById('row'+id).style.background="red";
}
function yellow(id) {
tmp=document.getElementById('row'+id).style.background="yellow";
}
function normal(id) {
if(id%2) tmp=document.getElementById('row'+id).style.background='<?php echo $color1 ?>';
else tmp=document.getElementById('row'+id).style.background='<?php echo $color2 ?>';
}
function check_all(action) {
	if(action=='unblock') toggle_stat=unblock_all_toggle_stat;
	else toggle_stat=del_all_toggle_stat;
	<?php
	echo "first=$first;\n";
	echo "	num=$num;\n";
	?>
	for(i=first; i<first+num; i++) {
		obj=document.getElementById(action+i);
		if(!obj) continue;
		if(toggle_stat) {
			obj.checked=false;
			//normal(i-first+1);
			//================
			if(action=='unblock') {
				if(document.getElementById('del'+(i-first+1)) && document.getElementById('del'+(i-first+1)).checked) red(i-first+1);
				else normal(i-first+1);
			}
			else {
				if(document.getElementById('unblock'+(i-first+1)) && document.getElementById('unblock'+(i-first+1)).checked) yellow(i-first+1);
				else normal(i-first+1);
			}
			//================
		}
		else {
			obj.checked=true;
			//if(action=='unblock') yellow(i-first+1);
			//else red(i-first+1);
			//================
			if(action=='unblock') {
				if(document.getElementById('del'+(i-first+1)) && document.getElementById('del'+(i-first+1)).checked) orange(i-first+1);
				else yellow(i-first+1);
			}
			else {
				if(document.getElementById('unblock'+(i-first+1)) && document.getElementById('unblock'+(i-first+1)).checked) orange(i-first+1);
				else red(i-first+1);
			}
			//================
		}
	}
	if(action=='unblock') unblock_all_toggle_stat=!unblock_all_toggle_stat;
	else del_all_toggle_stat=!del_all_toggle_stat;
	//if(del_all_toggle_stat) document.getElementById('check_all2').value='Unselect all';
	//else document.getElementById('check_all2').value='Select all';
}

function is_digit(e) {
	code = e.keyCode ? e.keyCode : e.which;
	if(code<48 || code>57) return false;
	else return true;
}

function validate_goto() {
<?php
echo '	last_page=', ceil($total/$per_page), ";\n";
?>
	page=document.getElementById('page').value;
	if(page<1 || page>last_page ) {
		alert(<?php
		echo "'", sprintf(tr('Page number must be between (including) 1 and %d.'), ceil($total/$per_page)), "'";
		?>);
		document.getElementById('page').value='';
		return false;
	}
	else return true;
}

</script>
</head>
<body bgcolor="#7587b0" <?php echo $page_dir; ?>>
<center>
<form action="" method="post" name="blocked_ips_form">
<?php
echo tr('Records '), $first, tr(' - '), $last, tr(' of '), $total;
echo '<table border cellpadding="3">';
echo '<input type="hidden" name="antixsrf_token" value="';
echo $_COOKIE['reg8log_antixsrf_token'];
echo '">';

require_once $index_dir.'include/func/func_duration2friendly_str.php';

echo '<tr style="background: brown; color: #fff"><th></th>';

echo '<th>';
echo "<a class='header' href='?per_page=$per_page&page=$page&sort_by=ip&sort_dir=";
if($sort_by=='ip' and $sort_dir=='asc') echo 'desc';
else echo 'asc';
echo "'>IP</a>";
if($sort_by=='ip') {
	echo '&nbsp;';
	if($sort_dir=='asc') echo '<img src="../image/sort_asc.gif">';
	else echo '<img src="../image/sort_desc.gif">';
}
echo "</th>";

echo '<th>';
echo "<a class='header' href='?per_page=$per_page&page=$page&sort_by=first_attempt&sort_dir=";
if($sort_by=='first_attempt' and $sort_dir=='asc') echo 'desc';
else echo 'asc';
echo "'>", tr('First attempt'), "</a>";
if($sort_by=='first_attempt') {
	echo '&nbsp;';
	if($sort_dir=='asc') echo '<img src="../image/sort_asc.gif">';
	else echo '<img src="../image/sort_desc.gif">';
}
echo "</th>";

echo '<th>';
echo "<a class='header' href='?per_page=$per_page&page=$page&sort_by=last_attempt&sort_dir=";
if($sort_by=='last_attempt' and $sort_dir=='asc') echo 'desc';
else echo 'asc';
echo "'>", tr('Last attempt'), "</a>";
if($sort_by=='last_attempt') {
	echo '&nbsp;';
	if($sort_dir=='asc') echo '<img src="../image/sort_asc.gif">';
	else echo '<img src="../image/sort_desc.gif">';
}
echo "</th>";

echo '<th>';
echo "<a class='header' href='?per_page=$per_page&page=$page&sort_by=last_username&sort_dir=";
if($sort_by=='last_username' and $sort_dir=='asc') echo 'desc';
else echo 'asc';
echo "'>", tr('Last username'), "</a>";
if($sort_by=='last_username') {
	echo '&nbsp;';
	if($sort_dir=='asc') echo '<img src="../image/sort_asc.gif">';
	else echo '<img src="../image/sort_desc.gif">';
}
echo "</th>";

echo '<th>', tr('Current status'), '</th>';

echo '<th  class="admin_action">', tr('Unblock'), '</th>';

echo '<th  class="admin_action">', tr('Delete log record'), '</th>';

echo '</tr>';

require $index_dir.'include/config/config_brute_force_protection.php';

require_once $index_dir.'include/func/func_inet.php';

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
	echo '<td>', inet_ntop2($rec['ip']), '</td>';
	echo '<td>', duration2friendly_str($req_time-$rec['first_attempt'], 2), tr(' ago'), '</td>';
	echo '<td>', duration2friendly_str($req_time-$rec['last_attempt'], 2), tr(' ago'), '</td>';
	echo '<td>', htmlspecialchars($rec['last_username'], ENT_QUOTES, 'UTF-8'), '</td>';
	echo '<td>';
	if($rec['unblocked']) {
		echo '<span style="color: blue" title="', tr('Unblocked by admin'), '">', tr('Unblocked'), '</span>';
		echo '<td>&nbsp;</td>';
	}
	else if(
	
		(
		strtolower($rec['last_username'])!='admin' and $req_time-$rec['first_attempt']<$ip_block_period and $rec['block_threshold']>=$ip_block_threshold
		)
		or
		(
		strtolower($rec['last_username'])=='admin' and $req_time-$rec['first_attempt']<$admin_ip_block_period and $rec['block_threshold']>=$admin_ip_block_threshold
		)

		) {
		echo '<span style="color: red" ';
		echo 'title="', tr('Block lift'), ': ', duration2friendly_str($ip_block_period-($req_time-$rec['first_attempt']), 2), tr(' later');
		echo '">', tr('Blocked'), '</span>';
		echo '<td><input type="checkbox" name="un', $rec['auto'], '" id="unblock', $row, '" value="unblock" onclick="unblock_click(', $i, ', ', 'this.checked)"></td>';
		echo '<input type="hidden" name="ip', $rec['auto'], '" value="', bin2hex($rec['ip']), '">';
		echo '<input type="hidden" name="t', $rec['auto'], '" value="', $rec['last_attempt'], '">';
		echo '<input type="hidden" name="a', $rec['auto'], '" value="', ((strtolower($rec['last_username'])=='admin')? '1':'0'), '">';
		$currently_blocked=true;
	}
	else {
		echo '<span style="color: #000" title="', tr('Block period elapsed'), '">', tr('Not blocked'), '</span>';
		echo '<td>&nbsp;</td>';
	}
	echo '</td>';
	echo '<td><input type="checkbox" name="', $rec['auto'], '" id="del', $row, '" value="del" onclick="delete_click(', $i, ', ', 'this.checked)"></td>';
	echo '</tr>';
}

echo '<tr ';
if(!$r) echo ' style="background: ', $color1;
else echo ' style="background: ', $color2;
echo '">';
echo '<td colspan="6" ><input type="submit" value="', tr('Execute admin commands'), '" style="color: #000;" name="admin_action"></td><td align="center"><input type="button" onclick="check_all(\'unblock\')" value="', tr('All'), '" disabled id="check_all2"></td><td align="center"><input type="button" onclick="check_all(\'del\')" value="', tr('All'), '" disabled id="check_all3"></td></tr>';
echo '</table>';

echo '<script>';
if(isset($currently_blocked)) echo "\ndocument.getElementById('check_all2').disabled=false;\n";
echo "\ndocument.getElementById('check_all3').disabled=false;\n";
echo '</script>';

require $index_dir.'include/page/admin/page_gen_paginated_page_links.php';

if($total>$per_pages[0]) {
	if($total<=$per_page) echo '<br>';
	echo '<br>', tr('Records per page'), ': <select name="per_page" onchange="document.blocked_ips_form.change_per_page.click()">';
	foreach($per_pages as $value) {
		if($value!=$per_page) echo "<option>$value</option>";
		else echo "<option selected>$value</option>";
	}
	echo '</select>&nbsp;<input type="submit" value="', tr('Show'), '" name="change_per_page" style="display: visible">';
	echo  '<script>
	document.blocked_ips_form.change_per_page.style.display="none";
	</script>';
}

?>
</form>
<a href="index.php"><?php echo tr('Admin operations'); ?></a><br><br>
<a href="../index.php"><?php echo tr('Login page'); ?></a>
</center>
<?php
require $index_dir.'include/page/page_foot_codes.php';
?>
</body>
</html>
