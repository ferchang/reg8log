var alert_box=document.getElementById('alert_div');
var alert_contents=document.getElementById('alert_contents_div');
var alert_title=document.getElementById('alert_title_div');

function hide_alert() {
	alert_box.style.visibility='hidden';
}

var winW = 630, winH = 460;

function get_window_dims() {
	if (document.body && document.body.offsetWidth) {
		winW = document.body.offsetWidth;
		winH = document.body.offsetHeight;
	}
	if (document.compatMode=='CSS1Compat' && document.documentElement && document.documentElement.offsetWidth) {
		winW = document.documentElement.offsetWidth;
		winH = document.documentElement.offsetHeight;
	}
	if (window.innerWidth && window.innerHeight) {
		winW = window.innerWidth;
		winH = window.innerHeight;
	}
}

function my_alert(title, msg) {
	while(msg.indexOf('\n')!=-1) msg=msg.replace('\n','<br>');
	while(title.indexOf('\n')!=-1) title=title.replace('\n','<br>');
	alert_title.innerHTML=title;
	alert_contents.innerHTML=msg;
	get_window_dims();
	alert_box.style.left=Math.floor(winW/2-(alert_box.offsetWidth/2))
	alert_box.style.top=Math.floor(winH/2-(alert_box.offsetHeight/2));
	alert_box.style.visibility='visible';
}

function dont_disturb(stat) {
	if(stat) document.cookie='reg8log_dont_disturb=1';
	else document.cookie='reg8log_dont_disturb=0';
}
