<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
define('CAN_INCLUDE', true);

$store_request_entropy_probability2=1;

require_once '../include/common.php';

require ROOT.'include/code/admin/code_require_admin.php';

if(config::get('show_statistics_in_admin_operations_page')) {
	require ROOT.'include/code/admin/code_get_statistics4admin.php';
	$flag=true;
}
else $flag=false;

?>

<html <?php echo PAGE_DIR; ?>>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<META HTTP-EQUIV="EXPIRES" CONTENT="0">
<title><?php echo func::tr('Admin operations'); ?></title>
<style>
li {
font-size: large;
margin: 7px;
}
.li_item {
font-size: large;
margin: 7px;
color: white;
}
</style>
</head>
<body bgcolor="#7587b0" text="#000000" link="#0000FF" vlink="#800080" alink="#FF0000" style="margin: 0;" <?php echo PAGE_DIR; ?>>
<table width="100%"  cellpadding="5" cellspacing="0" style="">
<tr>
<td valign="top">
</td>
<td  width="100%" valign="top">
<?php
require ROOT.'include/page/page_sections.php';
?>
</td>
</tr>
</table>
<center>
<table bgcolor="#7587b0" style="position: relative; <?php if(config::get('debug_mode')) echo ' top: -130px;'; else echo 'top: -125px;'; ?> ">
<tr><td>
<ul>
<li><?php echo func::tr('Accounts'); ?>:
<ul>
<li><a class="li_item" href="admin-accounts.php"><?php echo func::tr('Accounts'); ?></a><?php if($flag) echo "($accounts)"; ?>

<li><a class="li_item" href="admin-ban_user.php"><?php echo func::tr('Ban a user'); ?></a>
<li><a class="li_item" href="admin-unban_user.php"><?php echo func::tr('Unban a user'); ?></a>
<li><a class="li_item" href="admin-banned_users.php"><?php echo func::tr('Banned users'); ?></a><?php if($flag) echo "($banned_users)"; ?>
</ul>

<li><?php echo func::tr('Pending accounts'); ?>:
<ul>
<li><a class="li_item" href="admin-pending_accounts4admin.php"><?php echo func::tr('Accounts awaiting admin\'s confirmation'); ?></a><?php if($flag) echo "($pending_accounts4admin)"; ?>
<li><a class="li_item" href="admin-pending_accounts4email.php"><?php echo func::tr('Accounts awaiting email verification'); ?></a><?php if($flag) echo "($pending_accounts4email)"; ?>
</ul>

<li><?php echo func::tr('Security logs'); ?>:
<ul>
<li><a class="li_item" href="admin-account_blocks.php"><?php echo func::tr('Account blocks'); ?></a><?php if($flag) echo '<span title="', func::tr('Active/All'), '">(<span style="color: red">', $active_account_blocks, '</span>/', $all_account_blocks, ')</span>'; ?>
<li><a class="li_item" href="admin-ip_blocks.php"><?php echo func::tr('IP blocks'); ?></a><?php if($flag) echo '<span title="', func::tr('Active/All'), '">(<span style="color: red">', $active_ip_blocks, '</span>/', $all_ip_blocks, ')</span>'; ?>
</ul>

<li><a class="li_item" href="admin-tables_status.php"><?php echo func::tr('Tables status'); ?></a>
<?php
if(config::get('admin_error_log_access')) {
	
	echo '<li><a class="li_item" href="../debug_tools/error_log.php?admin_ops"';
	if(!config::get('log_errors')) {
		echo ' style="color: #bbb" ';
		echo ' title="', func::tr('Error logging is not enabled.'), '" ';
	}
	echo '>', func::tr('Error log'), '</a>';

	$error_log_file=ROOT.'file_store/error_log.php';

	if(!is_writable($error_log_file)) $new='!';
	else if(!is_readable($error_log_file)) $new='?';
	else {
		$logs=file_get_contents($error_log_file);
		if(substr($logs, 0, strlen(ERROR_LOG_CLEAR_STR))===ERROR_LOG_CLEAR_STR) $logs=substr($logs, strlen(ERROR_LOG_CLEAR_STR));
		if($logs===false) $logs='';
		if($logs==='') $new='0';
		else {
			$size=strlen($logs);
			$current_hash=substr(hash('sha256', $logs), 0, 32);
			$query="select last_hash from error_log_hash limit 1";
			$reg8log_db->query($query);
			$rec=$reg8log_db->fetch_row();
			if($rec['last_hash']==='' or $rec['last_hash']==='?') $new='+';
			else if($current_hash!==$rec['last_hash']) $new='+';
			else $new='-';
		}
	}

	if(isset($size)) $file_size_hint=func::tr('File size').': '.$size.' '.func::tr('bytes');

	echo '(';
	if($new==='+') echo "<span style='color: f00;' title='$file_size_hint'>", func::tr('New logs'), '</span>';
	else if($new==='-') echo "<span style='color: #000;' title='$file_size_hint'>", func::tr('No new logs'), '</span>';
	else if($new==='?') echo '<span style="color: orange;">', func::tr('Error log file'), ' ', func::tr('not readable'), '!</span>';
	else if($new==='0') echo '<span style="color: #0f0;">', func::tr('Empty'), '</span>';
	else if($new==='!') echo '<span style="color: #f00;">', func::tr('Error log file'), ' ', func::tr('not writable'), '!</span>';
	echo ')';
	
}

?>
</ul>
</td></tr>
</table>
</center>
<?php
require ROOT.'include/page/page_foot_codes.php';
?>
</body>
</html>
