<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(!isset($index_dir)) $index_dir='';

$parent_page=true;

?>

<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<META HTTP-EQUIV="EXPIRES" CONTENT="0">
<title>Ban user</title>
<style>
.unit {
	color: #8fd;
}
</style>
<script src="../js/common.js"></script>
<script language="javascript">

function show_duration_selects(show) {
	if(show==0) document.getElementById('ban_duration').style.visibility='hidden';
	else document.getElementById('ban_duration').style.visibility='visible';
}

function clear_form() {
	ban_form2.reason.value='';
	ban_form2.ban_type[0].click();
	ban_form2.years.options[0].selected=true;
	ban_form2.months.options[0].selected=true;
	ban_form2.days.options[0].selected=true;
	ban_form2.hours.options[0].selected=true;
	clear_cap(true);
	return false;
}

function validate() {//client side validator

	msgs=new Array();

	i=0;

	if(ban_form2.years.options[0].selected && ban_form2.months.options[0].selected && ban_form2.days.options[0].selected && ban_form2.hours.options[0].selected && !ban_form2.ban_type[1].checked) msgs[i++]="no ban duration specified!";

	if(msgs.length) {
		clear_cap(false);
		for(i in msgs){
			msgs[i]=msgs[i].charAt(0).toUpperCase()+msgs[i].substring(1, msgs[i].length);
			cap.appendChild(document.createTextNode(msgs[i]));
			cap.appendChild(document.createElement("br"));
		}
		return false;
	}

	return true;
}//client side validator

</script>
</head>
<body bgcolor="#D1D1E9" text="#000000" link="#0000FF" vlink="#800080" alink="#FF0000">
<table width="100%" height="100%"><tr><td align="center">
<form name="ban_form2" action="" method="post">
<table bgcolor="#7587b0" style="padding: 5px" >
<?php

if(!empty($err_msgs)) {
	echo '<tr align="center"><td colspan="3"  style="border: solid thin yellow; font-style: italic;"><span style="color: #800">Errors:</span><br />';
	foreach($err_msgs as $err_msg) {
		$err_msg[0]=strtoupper($err_msg[0]);
		echo "<span style=\"color: yellow\" >$err_msg</span><br />";
	}
	echo '</td></tr>';
}

echo '<input type="hidden" name="antixsrf_token" value="';
echo $_COOKIE['reg8log_antixsrf_token'];
echo '">';

require $index_dir.'include/code/code_generate_form_id.php';

?>

<tr align="center"><td>
<table border style="margin-top: 7px">
<tr style="background: brown; color: #fff"><th>Username</th><th>uid</th><th>Email</th><th>Gender</th><th>Member for</th></tr><tr style="background: #ccc" align="center">
<?php
echo '<td>', htmlspecialchars($rec['username'], ENT_QUOTES, 'UTF-8'), '</td>';
echo '<input type="hidden" name="username" value="', htmlspecialchars($rec['username'], ENT_QUOTES, 'UTF-8'), '">';
echo '<td>', $rec['uid'], '</td>';
echo '<td>', $rec['email'], '</td>';
echo '<td>', $rec['gender'], '</td>';
require_once $index_dir.'include/func/func_duration2msg.php';
echo '<td>', duration2friendly_str(time()-$rec['timestamp']), '</td>';
?>
</tr></table><br>
</td></tr>
<tr>
<td align="left" style="">Ban for a <span class="unit">duration</span> <input type="radio" name="ban_type" value="duration" onclick="show_duration_selects(1)" <?php if(isset($_POST['ban_type'])) {
if($_POST['ban_type']=='duration') echo ' checked="true" '; } else echo ' checked="true" '; ?>> or <span class="unit">infinitely</span> <input type="radio" name="ban_type" value="infinite" onclick="show_duration_selects(0)" <?php if(isset($_POST['ban_type']) and $_POST['ban_type']=='infinite') echo ' checked="true" '; ?>><br><br>
</td>
</tr>
<tr id="ban_duration" style="display: inline">
<td align="left">Ban for
<select name="years"><option>0</option><option>1</option></select> <span class="unit">year(s)</span> and
<select name="months"><option>0</option><option>1</option><option>2</option><option>3</option><option>6</option><option>9</option></select> <span class="unit">month(s)</span> and
<select name="days"><option>0</option><option>1</option><option>2</option><option>3</option><option>7</option><option>15</option><option>25</option></select> <span class="unit">day(s)</span> and
<select name="hours"><option>0</option><option>1</option><option>3</option><option>6</option><option>12</option><option>20</option></select> <span class="unit">hour(s)</span>
</td>
</tr>
<tr>
<td align="center"><br>Reason: <input type="text" name="reason" size="50" <?php if(isset($_POST['reason'])) echo 'value="', htmlspecialchars($_POST['reason'], ENT_QUOTES, 'UTF-8'), '"'; ?>></td>
</tr>
<tr>
<br><td align="center"><span style="color: yellow; font-style: italic" id="cap">&nbsp;</span></td>
</tr>
<tr>
<td align="center">
<input type="submit" value="Cancel" name="cancel" />
<input type="reset" value="Clear" onClick="return clear_form();"  />
<input type="submit" value="Ban" name="ban_form2" onClick="return validate()" /></td>
</tr></table>
</form>
<a href="admin_operations.php">Admin operations</a><br><br>
<a href="../index.php">Login page</a>
</td></tr></table>
</body>
</html>
