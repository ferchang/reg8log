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
<title><?php echo func::tr('Blocked IPs'); ?></title>
<style>
</style>
<script src="../js/forms_common.js"></script>
<?php require ROOT.'include/code/code_validate_captcha_field-js.php'; ?>
<script src="../js/sha256.js"></script>
<script>

<?php
echo "\nsite_salt='$site_salt';\n";
?>

<?php
if(isset($password_check_needed)) echo 'password_exists=true;';
else echo 'password_exists=false;';
?>

var del_all_toggle_stat=false;
var unblock_all_toggle_stat=false;

<?php
require ROOT.'include/page/admin/page_common_list_funcs-js.php';
?>

function clear_form() {
	document.blocked_ips_form.password.value='';
	if(document.blocked_ips_form.remember) document.blocked_ips_form.remember.checked=false;
	if(captcha_exists) document.getElementById('captcha_check_status').innerHTML='<?php echo func::tr('(Not case-sensitive)'); ?>';
	clear_cap(false);
	return false;
}

function hash_password() {
	document.blocked_ips_form.password.value='hashed-'+site_salt+'-'+hex_sha256(site_salt+document.blocked_ips_form.password.value);
}

function validate() {//client side validator

	clear_cap(true);

	msgs=new Array();

	i=0;

	if(password_exists) if(!document.blocked_ips_form.password.value.length) msgs[i++]="<?php echo func::tr('password field is empty!'); ?>";

	if(captcha_exists) validate_captcha(document.blocked_ips_form.captcha.value);

	if(msgs.length) {
	clear_cap(false);
	for(i in msgs){
		msgs[i]=msgs[i].charAt(0).toUpperCase()+msgs[i].substring(1, msgs[i].length);
		cap.appendChild(document.createTextNode(msgs[i]));
		cap.appendChild(document.createElement("br"));
	}
	return false;
	}

	if(captcha_exists) {
		form_obj=document.blocked_ips_form;
		check_captcha();
		return false;
	}

	if(password_exists) hash_password();

	return true;
}//client side validator

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

</script>
</head>
<body bgcolor="#7587b0" <?php echo $page_dir; ?>>
<center>
<?php
require ROOT.'include/page/admin/page_err_msgs.php';
?>
<form action="" method="post" name="blocked_ips_form">
<?php
echo func::tr('Records '), $first, func::tr(' - '), $last, func::tr(' of '), $total;
echo '<table border cellpadding="3">';
echo '<input type="hidden" name="antixsrf_token" value="';
echo $_SESSION['reg8log']['antixsrf_token4post'];
echo '">';

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
echo "'>", func::tr('First attempt'), "</a>";
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
echo "'>", func::tr('Last attempt'), "</a>";
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
echo "'>", func::tr('Last username'), "</a>";
if($sort_by=='last_username') {
	echo '&nbsp;';
	if($sort_dir=='asc') echo '<img src="../image/sort_asc.gif">';
	else echo '<img src="../image/sort_desc.gif">';
}
echo "</th>";

echo '<th>', func::tr('Current status'), '</th>';

echo '<th  class="admin_action">', func::tr('Unblock'), '</th>';

echo '<th  class="admin_action">', func::tr('Delete log record'), '</th>';

echo '</tr>';

require ROOT.'include/config/config_brute_force_protection.php';

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
	echo '<td>', func::inet_ntop2($rec['ip']), '</td>';
	echo '<td>', func::duration2friendly_str($req_time-$rec['first_attempt'], 2), func::tr(' ago'), '</td>';
	echo '<td>', func::duration2friendly_str($req_time-$rec['last_attempt'], 2), func::tr(' ago'), '</td>';
	echo '<td>', htmlspecialchars($rec['last_username'], ENT_QUOTES, 'UTF-8'), '</td>';
	echo '<td>';
	if($rec['unblocked']) {
		echo '<span style="color: blue" title="', func::tr('Unblocked by admin'), '">', func::tr('Unblocked'), '</span>';
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
		echo 'title="', func::tr('Block lift'), ': ', func::duration2friendly_str($ip_block_period-($req_time-$rec['first_attempt']), 2), func::tr(' later');
		echo '">', func::tr('Blocked'), '</span>';
		echo '<td><input type="checkbox" name="un', $rec['auto'], '" id="unblock', $row, '" value="unblock" onclick="unblock_click(', $i, ', ', 'this.checked)" ', ((isset($_POST['un'.$rec['auto']]))? ' checked ' : ''), '></td>';
		echo '<input type="hidden" name="ip', $rec['auto'], '" value="', bin2hex($rec['ip']), '">';
		echo '<input type="hidden" name="t', $rec['auto'], '" value="', $rec['last_attempt'], '">';
		echo '<input type="hidden" name="a', $rec['auto'], '" value="', ((strtolower($rec['last_username'])=='admin')? '1':'0'), '">';
		$currently_blocked=true;
	}
	else {
		echo '<span style="color: #000" title="', func::tr('Block period elapsed'), '">', func::tr('Not blocked'), '</span>';
		echo '<td>&nbsp;</td>';
	}
	echo '</td>';
	echo '<td><input type="checkbox" name="', $rec['auto'], '" id="del', $row, '" value="del" onclick="delete_click(', $i, ', ', 'this.checked)" ', ((isset($_POST[$rec['auto']]))? ' checked ' : ''), '></td>';
	echo '</tr>';
	
	if(isset($_POST['un'.$rec['auto']], $_POST[$rec['auto']])) $boths[]=$i;
	else if(isset($_POST['un'.$rec['auto']])) $unblocks[]=$i;
	else if(isset($_POST[$rec['auto']])) $dels[]=$i;
}

echo '<tr ';
if(!$r) echo ' style="background: ', $color1;
else echo ' style="background: ', $color2;
echo '">';
echo '<td colspan="6" >';

require ROOT.'include/page/admin/page_captcha8password_fields.php';

echo '<input type="submit" value="', func::tr('Execute admin commands'), '" style="color: #000;" name="admin_action" onclick="return validate();">';
if(isset($captcha_needed) and !$captcha_verified) echo '<br>';
else echo '&nbsp;&nbsp;&nbsp;';
echo '<span style="color: red; font-style: italic" id="cap"></span></td></tr></table>';

echo '</td><td align="center" valign=top><input type="button" onclick="check_all(\'unblock\')" value="', func::tr('All'), '" disabled id="check_all2"></td><td align="center" valign=top><input type="button" onclick="check_all(\'del\')" value="', func::tr('All'), '" disabled id="check_all3"></td></tr>';
echo '</table>';

echo '<script>';
if(isset($currently_blocked)) echo "\ndocument.getElementById('check_all2').disabled=false;\n";
echo "\ndocument.getElementById('check_all3').disabled=false;\n";
echo '</script>';

require ROOT.'include/page/admin/page_gen_paginated_page_links.php';

$form_name='blocked_ips_form';
require ROOT.'include/page/admin/page_per_pages_select.php';

?>
</form>
<a href="index.php"><?php echo func::tr('Admin operations'); ?></a><br><br>
<a href="../index.php"><?php echo func::tr('Login page'); ?></a>
</center>
<script>
if(captcha_exists) {
	document.getElementById('re_captcha_msg').style.visibility='visible';
	captcha_img_style=document.getElementById('captcha_image').style;
	captcha_img_style.cursor='hand';
	if(captcha_img_style.cursor!='hand') captcha_img_style.cursor='pointer';
}
</script>
<script>

boths=new Array(<?php
$flag=false;
if(isset($boths)) foreach($boths as $v) {
	if($flag) echo ', ';
	else $flag=true;
	echo "'$v'";
}
?>);
for(var i in boths) orange(boths[i]);

unblocks=new Array(<?php
$flag=false;
if(isset($unblocks)) foreach($unblocks as $v) {
	if($flag) echo ', ';
	else $flag=true;
	echo "'$v'";
}
?>);
for(var i in unblocks) if(document.getElementById('unblock'+unblocks[i])) yellow(unblocks[i]);

dels=new Array(<?php
$flag=false;
if(isset($dels)) foreach($dels as $v) {
	if($flag) echo ', ';
	else $flag=true;
	echo "'$v'";
}
?>);
for(var i in dels) red(dels[i]);

</script>
<?php
require ROOT.'include/page/page_foot_codes.php';
?>
</body>
</html>
