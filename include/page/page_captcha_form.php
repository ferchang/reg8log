<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(isset($captcha_form4login)) echo '<table>';
// we need captcha from in its own table so we can remove it dynamically (with Javascript) from the login from.

?>

<tr>
<td ><?php echo tr('This is the security code'); ?>:<br /><span id="re_captcha_msg" style="visibility: hidden"><?php echo tr('If the code is not readable,<br />click on the image to change it'); ?>.</span></td>
<td align="left" valign="center">
<img src="captcha/captcha_image.php?1" style="border: 2px solid #000;" onclick="mycaptcha('change');" onload="mycaptcha('loaded');" onerror="mycaptcha('error');" id="captcha_image" title="" />
</td>
<td>
<img src="image/throbber.gif" style="visibility: hidden; border: 2px solid #000" id="captcha_throbber" /></td>
</td>
</tr>
<tr>
<td colspan="3">
<?php echo tr('captcha - never used letters'); ?>
</td>
</tr>
<tr>
<td <?php echo $cell_align; ?> valign="top"><?php echo tr('Enter the security code here'); ?>:</td>
<td align="center" colspan="2"><input type="text" size="6" name="captcha" id="captcha" style="" autocomplete="off" onfocus="captcha_focus=true" onblur="t=setTimeout('captcha_focus=false' , 200)" style="vertical-align: middle" />&nbsp;<img src="image/throbber.gif" id="captcha_check_throbber" style="display: none; border: 1px solid #000; width: 17px; height: 17px; vertical-align: middle; margin-left: 5px; margin-right: 5px" /><span id="captcha_check_status"><?php echo tr('(Not case-sensitive)'); ?></span></td>
</tr>
<script>
//copy the same code into add_captcha function in page_login_form.php
captcha_exists=true;
<?php
require_once $index_dir.'include/config/config_register_fields.php';
echo "captcha_min_len={$fields['captcha']['minlength']};\n";
echo "captcha_max_len={$fields['captcha']['maxlength']};\n";
echo "captcha_re=";
if($fields['captcha']['js_re']===true) echo $fields['captcha']['php_re'];
else if($fields['captcha']['js_re']===false) echo 'false';
else echo $fields['captcha']['js_re'];
echo ";\n";
?>
</script>
<?php
if(isset($captcha_form4login)) echo '</table>';
?>
