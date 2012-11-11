function check_admin(val) {
	if(login2ip_change) {
		login2ip_change=false;
		return;
	}
	if(val.toLowerCase()=='admin') if(tie_login2ip==1) document.getElementById('login2ip_checkbox').checked=true;
	else if(tie_login2ip==2) document.getElementById('login2ip_checkbox').checked=false;
}
