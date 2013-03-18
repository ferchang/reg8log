<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

?>

<script>
function validate_captcha(val) {

	if(val=='') msgs[i++]='<?php echo tr('Security code field is empty!'); ?>';
	else {
		if(captcha_re && !captcha_re.test(val)) msgs[i++]='<?php echo tr('Security code contains invalid characters!'); ?>';
		if(val.length<captcha_min_len) msgs[i++]='<?php echo tr('Security code short - js msg'); ?>';
		else if(val.length>captcha_max_len) msgs[i++]='<?php echo tr('Security code long - js msg'); ?>';
	}

}
</script>
