<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

echo '<table cellpadding="5" style="border: thin solid black">';

if(config::get('debug_mode')) echo '<tr bgcolor="#e1cfa0" title="', func::tr('debug mode turn off instruction msg'),'"><td><div style="border: medium solid #f00; padding: 2px; color: #f00; font-weight: bold; background: yellow; text-align: center">', func::tr('Warning: Debug mode on!'), '</div></td></tr>';

$index_dir=func::get_relative_root_path();

echo '<tr  bgcolor="#e1cfa0" ><td>
<a href="', $index_dir, 'index.php">', func::tr('Login page'), '</a>&nbsp;|&nbsp;
<a href="', $index_dir, 'user_options.php">', func::tr('User options'), '</a><br>
<a href="', $index_dir, 'register.php">', func::tr('Register'), '</a>&nbsp;|&nbsp;
<a href="', $index_dir, 'change_lang.php?antixsrf_token=', $_SESSION['reg8log']['antixsrf_token4get'], '" onclick="this.href=this.href+\'&addr=\'+location.href">', func::tr('Change language'), '</a><br>
<a href="', $index_dir, 'email_verification_link_request.php">', func::tr('Resend email verification link'), '</a><br>
<a href="', $index_dir, 'admin/index.php">', func::tr('Admin operations'), '</a>
<hr style="background: #000; height: 3px; max-height: 3px; margin-top: 10px; margin-bottom: 5px; color: #000">
<div dir="ltr"><a href="', $index_dir, 'debug_tools/show_session_contents.php">Show project session contents</a><br>
<a href="', $index_dir, 'debug_tools/show_cookies.php">Show project cookies</a></div>
</td></tr>
</table>';

?>