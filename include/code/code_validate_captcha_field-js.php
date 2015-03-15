<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

?>

<script>

var captcha_client_error=false;

function validate_captcha(val) {

	tmp=i;

	if(val=='') msgs[i++]='<?php echo func::tr('Security code field is empty!'); ?>';
	else {
		if(captcha_re && !captcha_re.test(val)) msgs[i++]='<?php echo func::tr('Security code contains invalid characters!'); ?>';
		if(val.length<captcha_min_len) msgs[i++]='<?php echo func::tr('Security code short - js msg'); ?>';
		else if(val.length>captcha_max_len) msgs[i++]='<?php echo func::tr('Security code long - js msg'); ?>';
	}

	if(i!=tmp) captcha_client_error=true;
	else captcha_client_error=false;
	
}

//-----------------------------------

var captcha_code_correct=false;

function check_captcha() {
	if(!captcha_exists) return;
	
	document.getElementById('captcha_check_throbber').style.display='';
	document.getElementById('captcha_check_status').innerHTML='<span style="color: #000;"><?php echo func::tr('Checking the security code...'); ?></span>';
	
	if(window.XMLHttpRequest) xhr = new XMLHttpRequest();
	else if (window.ActiveXObject) xhr = new ActiveXObject("Microsoft.XMLHTTP");

	xhr.open('POST', '<?php echo ROOT; ?>ajax/check_captcha_code.php', true);
	xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

	xhr.onreadystatechange=function() {
		if(xhr.readyState == 4) if(xhr.status == 200) {
			document.getElementById('captcha_check_throbber').style.display='none';
			if(xhr.responseText=='y') {
				try { hash_password(); } catch(e) { }
				form_obj.submit();
			}
			else if(xhr.responseText=='n') {
				document.getElementById('captcha_check_status').innerHTML='<span style="color: yellow"><?php echo func::tr('Security code was incorrect!'); ?></span>';
				mycaptcha('change');
				document.getElementById('captcha').focus();
				return;
			}
			else {
				try { hash_password(); } catch(e) { }
				form_obj.submit();
			}
		}
		else {
			document.getElementById('captcha_check_throbber').style.display='none';
			try { hash_password(); } catch(e) { }
			form_obj.submit();
		}
	}

	xhr.send('captcha='+document.getElementById('captcha').value+'&antixsrf_token=<?php echo $_SESSION['reg8log']['reg8log_antixsrf_token4post']; ?>');
}

</script>
