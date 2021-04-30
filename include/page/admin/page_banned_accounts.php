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
<link href="../css/list.css" media="screen" rel="stylesheet" type="text/css" />
<title><?php echo func::tr('Banned users'); ?></title>
<style>
</style>
<script src="../js/forms_common.js"></script>
<script>
<?php
require ROOT.'include/page/admin/page_common_list_funcs-js.php';
?>
</script>
<?php require ROOT.'include/code/code_validate_captcha_field-js.php'; ?>
<script src="../js/sha256.js"></script>

<script>

<?php
echo "\nsite_salt='".SITE_SALT."';\n";
?>

<?php
if(isset($password_check_needed)) echo 'password_exists=true;';
else echo 'password_exists=false;';
?>

//-----------------------

function clear_form() {
	document.banned_users_form.password.value='';
	if(document.banned_users_form.remember) document.banned_users_form.remember.checked=false;
	if(captcha_exists) document.getElementById('captcha_check_status').innerHTML='<?php echo func::tr('(Not case-sensitive)'); ?>';
	clear_cap(false);
	return false;
}

function hash_password() {
	document.banned_users_form.password.value='hashed-'+site_salt+'-'+hex_sha256(site_salt+document.banned_users_form.password.value);
}

function validate() {//client side validator

	clear_cap(true);

	msgs=new Array();

	i=0;

	if(password_exists) if(!document.banned_users_form.password.value.length) msgs[i++]="<?php echo func::tr('password field is empty!'); ?>";

	if(captcha_exists) validate_captcha(document.banned_users_form.captcha.value);

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
		form_obj=document.banned_users_form;
		check_captcha();
		return false;
	}

	if(password_exists) hash_password();

	return true;
}//client side validator

//-----------------------

var unban_all_toggle_stat=false;

<?php
require ROOT.'include/page/admin/page_common_list_funcs-js.php';
?>

function unban_clicked(id, checked) {
	if(!checked) normal(id);
	else green(id);
}

function check_all(action) {
	<?php
	echo "first=$first;\n";
	echo "	num=$num;\n";
	?>
	for(i=first; i<first+num; i++) {
		obj=document.getElementById(action+i);
		if(unban_all_toggle_stat) {
			obj.checked=false;
			normal(i-first+1);
		}
		else {
			obj.checked=true;
			green(i-first+1);
		}
	}
	unban_all_toggle_stat=!unban_all_toggle_stat;
	//if(unban_all_toggle_stat) document.getElementById('check_all2').value='Unselect all';
	//else document.getElementById('check_all2').value='Select all';
}

</script>

</head>
<body bgcolor="#7587b0" <?php echo PAGE_DIR; ?>>
<center>
<?php
require ROOT.'include/page/admin/page_err_msgs.php';
?>
<form action="" method="post" name="banned_users_form">
<?php

echo '<input type="hidden" name="antixsrf_token" value="';
echo ANTIXSRF_TOKEN4POST;
echo '">';

require ROOT.'include/code/code_generate_form_id.php';

echo func::tr('Records '), $first, func::tr(' - '), $last, func::tr(' of '), $total;
echo '<table border cellpadding="3">';

echo '<tr style="background: brown; color: #fff"><th></th>';

echo '<th>';
echo "<a class='header' href='?per_page=$per_page&page=$page&sort_by=username&sort_dir=";
if($sort_by==='username' and $sort_dir==='asc') echo 'desc';
else echo 'asc';
echo "'>", func::tr('Username'), "</a>";
if($sort_by==='username') {
	echo '&nbsp;';
	if($sort_dir==='asc') echo '<img src="../image/sort_asc.gif">';
	else echo '<img src="../image/sort_desc.gif">';
}
echo "</th>";

echo '<th>';
echo "<a class='header' href='?per_page=$per_page&page=$page&sort_by=uid&sort_dir=";
if($sort_by==='uid' and $sort_dir==='asc') echo 'desc';
else echo 'asc';
echo "'>", func::tr('Uid'), "</a>";
if($sort_by==='uid') {
	echo '&nbsp;';
	if($sort_dir==='asc') echo '<img src="../image/sort_asc.gif">';
	else echo '<img src="../image/sort_desc.gif">';
}
echo "</th>";

echo '<th>';
echo "<a class='header' href='?per_page=$per_page&page=$page&sort_by=gender&sort_dir=";
if($sort_by==='gender' and $sort_dir==='asc') echo 'desc';
else echo 'asc';
echo "'>", func::tr('Gender'), "</a>";
if($sort_by==='gender') {
	echo '&nbsp;';
	if($sort_dir==='asc') echo '<img src="../image/sort_asc.gif">';
	else echo '<img src="../image/sort_desc.gif">';
}
echo "</th>";

echo '<th>';
echo "<a class='header' href='?per_page=$per_page&page=$page&sort_by=email&sort_dir=";
if($sort_by==='email' and $sort_dir==='asc') echo 'desc';
else echo 'asc';
echo "'>", func::tr('Email'), "</a>";
if($sort_by==='email') {
	echo '&nbsp;';
	if($sort_dir==='asc') echo '<img src="../image/sort_asc.gif">';
	else echo '<img src="../image/sort_desc.gif">';
}
echo "</th>";

echo '<th>';
echo "<a class='header' href='?per_page=$per_page&page=$page&sort_by=timestamp&sort_dir=";
if($sort_by==='timestamp' and $sort_dir==='asc') echo 'desc';
else echo 'asc';
echo "'>", func::tr('Member for'), "</a>";
if($sort_by==='timestamp') {
	echo '&nbsp;';
	if($sort_dir==='asc') echo '<img src="../image/sort_asc.gif">';
	else echo '<img src="../image/sort_desc.gif">';
}
echo "</th>";

echo '<th>';
echo "<a class='header' href='?per_page=$per_page&page=$page&sort_by=banned&sort_dir=";
if($sort_by==='banned' and $sort_dir==='asc') echo 'desc';
else echo 'asc';
echo "'>", func::tr('Ban until'), "</a>";
if($sort_by==='banned') {
	echo '&nbsp;';
	if($sort_dir==='asc') echo '<img src="../image/sort_asc.gif">';
	else echo '<img src="../image/sort_desc.gif">';
}
echo "</th>";

echo '<th>';
echo "<a class='header' href='?per_page=$per_page&page=$page&sort_by=reason&sort_dir=";
if($sort_by==='reason' and $sort_dir==='asc') echo 'desc';
else echo 'asc';
echo "'>", func::tr('Ban reason'), "</a>";
if($sort_by==='reason') {
	echo '&nbsp;';
	if($sort_dir==='asc') echo '<img src="../image/sort_asc.gif">';
	else echo '<img src="../image/sort_desc.gif">';
}
echo "</th>";

echo '<th  class="admin_action">', func::tr('Unban2'), '</th>';

echo '</tr>';

$i=0;
$r=false;
while($rec=$GLOBALS['reg8log_db']->fetch_row()) {
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
	if($rec['gender']==='n') echo '?';
	else if($rec['gender']==='m') echo func::tr('Male');
	else echo func::tr('Female');
	echo '</td>';
	func::print_email_td($rec);
	echo '<td>', func::duration2friendly_str(REQUEST_TIME-$rec['timestamp'], 2), '</td>';
	echo '<td>';
	if($rec['banned']==1) echo func::tr('Unlimited');
	else echo func::duration2friendly_str($rec['banned']-REQUEST_TIME, 2), func::tr(' later');
	echo '</td>';
	if(is_null($rec['reason'])) echo '<td title="', func::tr('No corresponding ban_info record found'), '"><span style="color: yellow">?</span>';
	else if($rec['reason']!=='') echo '<td>', $rec['reason'];
	else echo '<td title="', func::tr('No ban reason specified'), '">&nbsp;';
	echo '</td>';
	//--------------------------
	echo '<td><input type="checkbox" name="', "{$rec['auto']}:{$rec['username']}", '" id="unban', $row, '" value="unban" onclick="unban_clicked(', $i, ', ', 'this.checked)"';
	if(isset($_POST["{$rec['auto']}:{$rec['username']}"])) {
		echo ' checked ';
		$checkeds[]=$i;
	}
	echo '></td>';
	//--------------------------
	echo '</tr>';
}

echo '<tr style="background: rgb(209,209,165)" >';
$colspan=8;
echo "<td colspan=\"$colspan\">";

//----------------------------
require ROOT.'include/page/admin/page_captcha8password_fields.php';

echo '<input type="submit" value="', func::tr('Unban selected accounts'), '" style="color: #00f;" name="unban" onclick="return validate();">';
if(isset($captcha_needed) and !$captcha_verified) echo '<br>';
else echo '&nbsp;&nbsp;&nbsp;';
echo '<span style="color: red; font-style: italic" id="cap"></span></td></tr></table>';

echo '</td><td valign=top><input type="button" onclick="check_all(\'unban\')" value="', func::tr('All'), '" disabled id="check_all2"></td></tr>';
//----------------------------
echo '</table>';

echo '<script>';
echo "\ndocument.getElementById('check_all2').disabled=false;\n";
echo '</script>';

require ROOT.'include/page/admin/page_gen_paginated_page_links.php';

$form_name='banned_users_form';
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
checkeds=new Array(<?php
$flag=false;
if(isset($checkeds)) foreach($checkeds as $v) {
	if($flag) echo ', ';
	else $flag=true;
	echo "'$v'";
}
?>);
for(var i in checkeds) green(checkeds[i]);
</script>
<?php
require ROOT.'include/page/page_foot_codes.php';
?>
</body>
</html>

