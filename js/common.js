captcha_focus=false;

captcha_exists=false;

function clear_cap(br)
{
  for(i=cap.childNodes.length-1; i>=0; i--) {
    cap.removeChild(cap.childNodes[i]);
  }
  if(br) cap.appendChild(document.createElement("br"));
}

function mycaptcha(arg) {
  img=document.getElementById('captcha_image');
  throbber=document.getElementById('captcha_throbber');
  switch(arg) {
    case 'change':
      img.src=img.src+'1';
      throbber.style.visibility='visible';
      if(captcha_focus) {
        clearTimeout(t);
        document.getElementById('captcha').focus();
      }
    break;
    case 'loaded':
      throbber.style.visibility='hidden';
      document.getElementById('captcha').value='';
    break;
    case 'error':
      throbber.style.visibility='hidden';
    break;
  }
}

var captcha_min_len;
var captcha_max_len;
var captcha_re;

function validate_captcha(val) {

	if(val=='') msgs[i++]='Security code field is empty!';
	else {
		if(captcha_re && !captcha_re.test(val)) msgs[i++]='Security code contains invalid characters!';
		if(val.length<captcha_min_len) msgs[i++]='Security code is shorter than '+captcha_min_len+' characters!';
		else if(val.length>captcha_max_len) msgs[i++]='Security code is longer than '+captcha_max_len+' characters!';
	}

}
