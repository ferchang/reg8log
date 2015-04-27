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
<title><?php echo func::tr('Accounts awaiting admin\'s confirmation'); ?></title>
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

<?php
require ROOT.'include/page/admin/page_common_list_funcs-js.php';
?>

//--------------------------

function clear_form() {
	document.admin_confirmation_form.password.value='';
	if(document.admin_confirmation_form.remember) document.admin_confirmation_form.remember.checked=false;
	if(captcha_exists) document.getElementById('captcha_check_status').innerHTML='<?php echo func::tr('(Not case-sensitive)'); ?>';
	clear_cap(false);
	return false;
}

function hash_password() {
	document.admin_confirmation_form.password.value='hashed-'+site_salt+'-'+hex_sha256(site_salt+document.admin_confirmation_form.password.value);
}

function validate() {//client side validator

	clear_cap(true);

	msgs=new Array();

	i=0;

	if(password_exists) if(!document.admin_confirmation_form.password.value.length) msgs[i++]="<?php echo func::tr('password field is empty!'); ?>";

	if(captcha_exists) validate_captcha(document.admin_confirmation_form.captcha.value);

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
		form_obj=document.admin_confirmation_form;
		check_captcha();
		return false;
	}

	if(password_exists) hash_password();

	return true;
}//client side validator

//--------------------------

function check_all(action) {
	<?php
	echo "first=$first;\n";
	echo "	num=$num;\n";
	?>
	for(i=first; i<first+num; i++) {
		obj=document.getElementById(action+i);
		obj.click();
	}
}

</script>
</head>
<body bgcolor="#7587b0" <?php echo PAGE_DIR; ?>>
<?php

if(!empty($nonexistent_records)) echo '<center style="color: orange; background: #000; padding: 3px; font-weight: bold; margin-bottom: 5px">Info: ', $nonexistent_records, ' record(s) did not exist.</center>';
?>
<center>
<?php
require ROOT.'include/page/admin/page_err_msgs.php';
?>
<form action="" method="post" name="admin_confirmation_form">
<?php
echo func::tr('Records '), $first, func::tr(' - '), $last, func::tr(' of '), $total;
echo '<table border cellpadding="3">';
echo '<input type="hidden" name="antixsrf_token" value="';
echo ANTIXSRF_TOKEN4POST;
echo '">';

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
echo "<a class='header' href='?per_page=$per_page&page=$page&sort_by=emails_sent&sort_dir=";
if($sort_by==='emails_sent' and $sort_dir==='asc') echo 'desc';
else echo 'asc';
echo "'>", func::tr('Emails sent'), "</a>";
if($sort_by==='emails_sent') {
	echo '&nbsp;';
	if($sort_dir==='asc') echo '<img src="../image/sort_asc.gif">';
	else echo '<img src="../image/sort_desc.gif">';
}
echo "</th>";

echo '<th>';
echo "<a class='header' href='?per_page=$per_page&page=$page&sort_by=email_verified&sort_dir=";
if($sort_by==='email_verified' and $sort_dir==='asc') echo 'desc';
else echo 'asc';
echo "'>", func::tr('Email verified'), "</a>";
if($sort_by==='email_verified') {
	echo '&nbsp;';
	if($sort_dir==='asc') echo '<img src="../image/sort_asc.gif">';
	else echo '<img src="../image/sort_desc.gif">';
}
echo "</th>";

echo '<th>';
echo "<a class='header' href='?per_page=$per_page&page=$page&sort_by=timestamp&sort_dir=";
if($sort_by==='timestamp' and $sort_dir==='asc') echo 'desc';
else echo 'asc';
echo "'>", func::tr('Time'), "</a>";
if($sort_by==='timestamp') {
	echo '&nbsp;';
	if($sort_dir==='asc') echo '<img src="../image/sort_asc.gif">';
	else echo '<img src="../image/sort_desc.gif">';
}
echo "</th>";

if(config::get('can_notify_user_about_admin_action')) {

	echo '<th title="', func::tr('notify user description'), '">';
	echo "<a class='header' href='?per_page=$per_page&page=$page&sort_by=notify_user&sort_dir=";
	if($sort_by==='notify_user' and $sort_dir==='asc') echo 'desc';
	else echo 'asc';
	echo "'>", func::tr('Notify'), "</a>";
	if($sort_by==='notify_user') {
		echo '&nbsp;';
		if($sort_dir==='asc') echo '<img src="../image/sort_asc.gif">';
		else echo '<img src="../image/sort_desc.gif">';
	}
	echo "</th>";

}

echo '<th class="admin_action">', func::tr('Approve'), '</th><th class="admin_action">', func::tr('Delete'), '</th><th class="admin_action">?</th></tr>';

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
	echo '<td>';
	if($rec['gender']==='n') echo '?';
	else if($rec['gender']==='m') echo func::tr('Male');
	else echo func::tr('Female');
	echo '</td>';
	echo '<td>', $rec['email'], '</td>';
	if(config::get('can_notify_user_about_admin_action') and $rec['notify_user']) {
		echo '<input type="hidden" name="email-', $rec['auto'], '" value="', $rec['email'], '">';
		echo '<input type="hidden" name="lang-', $rec['auto'], '" value="', $rec['lang'], '">';
	}
	echo '<td>', $rec['emails_sent'], '</td>';
	if($rec['email_verified']) echo '<td style="color: green">', func::tr('Yes'), '</td>';
	else {
		if($rec['email_verification_key']) echo '<td style="color: red" title="', func::tr('user needs email verification msg'), '">', func::tr('No'), '</td>';
		else echo '<td style="color: yellow" title="', func::tr('Does not need email verification'), '">', func::tr('No'), '</td>';
	}
	echo '<td>', func::duration2friendly_str($req_time-$rec['timestamp'], 2), func::tr(' ago'), '</td>';
	if(config::get('can_notify_user_about_admin_action')) echo '<td>', ($rec['notify_user'])? func::tr('Yes'):func::tr('No'), '</td>';
	echo '<td><input type="radio" name="', $rec['auto'], '" id="appr', $row, '" value="appr" onclick="green(', $i,')" ', ((isset($_POST[$rec['auto']]) and $_POST[$rec['auto']]==='appr')? ' checked ' : ''), '></td>';
	echo '<td><input type="radio" name="', $rec['auto'], '" id="del', $row, '" value="del" onclick="red(', $i,')" ', ((isset($_POST[$rec['auto']]) and $_POST[$rec['auto']]==='del')? ' checked ' : ''), '></td>';
	echo '<td><input type="radio" name="', $rec['auto'], '" id="undet', $row, '" value="undet"  onclick="normal(', $i,')" ', ((!isset($_POST[$rec['auto']]) or (isset($_POST[$rec['auto']]) and $_POST[$rec['auto']]==='undet'))? ' checked ' : ''), '></td>';
	echo '</tr>';
	
	if(isset($_POST[$rec['auto']]))
	if($_POST[$rec['auto']]==='appr') $apprs_[]=$i;
	else if($_POST[$rec['auto']]==='del') $dels_[]=$i;
	
}
echo '<tr ';
if(!$r) echo ' style="background: ', $color1;
else echo ' style="background: ', $color2;
echo '">';
if(config::get('can_notify_user_about_admin_action')) echo '<td colspan="8">';
else echo '<td colspan="7">';

require ROOT.'include/page/admin/page_captcha8password_fields.php';

echo '<input type="submit" value="', func::tr('Execute admin commands'), '" style="color: red;" name="admin_action" onclick="return validate();">';
if(isset($captcha_needed) and !$captcha_verified) echo '<br>';
else echo '&nbsp;&nbsp;&nbsp;';
echo '<span style="color: red; font-style: italic" id="cap"></span></td></tr></table>';

echo '</td><td valign=top><input type="button" onclick="check_all(\'appr\')" value="', func::tr('All'), '" disabled id="check_all1"></td><td align="center" valign=top><input type="button" onclick="check_all(\'del\')" value="', func::tr('All'), '" disabled id="check_all2"></td><td align="center" valign=top><input type="button" onclick="check_all(\'undet\')" value="', func::tr('All'), '" disabled id="check_all3"></td></tr>';
echo '</table>';

echo '<script>';
echo "\ndocument.getElementById('check_all1').disabled=false;\ndocument.getElementById('check_all2').disabled=false;\ndocument.getElementById('check_all3').disabled=false;\n";
echo '</script>';

require ROOT.'include/page/admin/page_gen_paginated_page_links.php';

$form_name='admin_confirmation_form';
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
apprs=new Array(<?php
$flag=false;
if(isset($apprs_)) foreach($apprs_ as $v) {
	if($flag) echo ', ';
	else $flag=true;
	echo "'$v'";
}
?>);
for(var i in apprs) green(apprs[i]);
//----------------
dels=new Array(<?php
$flag=false;
if(isset($dels_)) foreach($dels_ as $v) {
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
