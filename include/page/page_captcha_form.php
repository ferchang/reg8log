<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(!isset($index_dir)) $index_dir='';

?>

<tr>
<td align="left">This is the security code:<br /><span id="re_captcha_msg" style="visibility: hidden">If the code is not readable,<br />click on the image to change it.</span></td>
<td align="left" valign="center">
<img src="captcha/captcha_image.php?1" style="border: 2px solid #000;" onclick="mycaptcha('change');" onload="mycaptcha('loaded');" onerror="mycaptcha('error');" id="captcha_image" />
</td>
<td>
<img src="image/throbber.gif" style="visibility: hidden; border: 2px solid #000" id="captcha_throbber" /></td>
</td>
</tr>
<tr>
<td align="right" valign="top">Enter the security code here:</td>
<td align="center" colspan="2"><input type="text" size="6" name="captcha" id="captcha" style="" autocomplete="off" onfocus="captcha_focus=true" onblur="t=setTimeout('captcha_focus=false' , 200)" />&nbsp; (Not case-sensitive)</td>
</tr>
<script>
captcha_exists=true;
<?php
require_once $index_dir.'include/info/info_register_fields.php';
echo "captcha_min_len={$fields['captcha']['minlength']};\n";
echo "captcha_max_len={$fields['captcha']['maxlength']};\n";
echo "captcha_re=";
if($fields['captcha']['js_re']===true) echo $fields['captcha']['php_re'];
else if($fields['captcha']['js_re']===false) echo 'false';
else echo $fields['captcha']['js_re'];
echo ";\n";
?>
</script>
