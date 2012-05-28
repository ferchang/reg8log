<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(!isset($index_dir)) $index_dir='';

echo '<table cellpadding="5" style="border: thin solid black">';

if($debug_mode) echo '<tr bgcolor="#e1cfa0" ><td><div style="border: medium solid #f00; padding: 2px; color: #f00; font-weight: bold; background: yellow">Warning: Debug mode on!</div></td></tr>';

if($index_dir=='../') echo '<tr  bgcolor="#e1cfa0" ><td>
<a href="../index.php">Login page</a><br>
<a href="../user_options.php">User options</a><br>
<a href="../register.php">Register</a><br>
<a href="../resend_email_verification_link.php">Resend email verification link</a><br>
<a href="../admin/admin_operations.php">Admin operations</a>
<hr />
<a href="../debug_tools/show_session_contents.php">Show project session contents</a><br>
<a href="../debug_tools/show_cookies.php">Show project cookies</a><br>
</td></tr>
</table>';
else echo '<tr  bgcolor="#e1cfa0" ><td>
<a href="index.php">Login page</a><br>
<a href="user_options.php">User options</a><br>
<a href="register.php">Register</a><br>
<a href="resend_email_verification_link.php">Resend email verification link</a><br>
<a href="admin/admin_operations.php">Admin operations</a>
<hr />
<a href="debug_tools/show_session_contents.php">Show project session contents</a><br>
<a href="debug_tools/show_cookies.php">Show project cookies</a><br>
</td></tr>
</table>';

?>