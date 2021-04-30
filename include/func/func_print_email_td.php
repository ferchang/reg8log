<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

function print_email_td($rec) {

	echo '<td ';
	if($rec['email_verified']) echo 'style="color: green" title="Email is verified">';
	else echo 'title="Email is not verified">';
	echo $rec['email'];
	echo '</td>';

}

?>
