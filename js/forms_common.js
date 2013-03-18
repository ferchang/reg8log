var captcha_focus=false;

var captcha_exists=false;

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
