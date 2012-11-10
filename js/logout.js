function onLogout() {
	exp = new Date();
	exp.setTime(exp.getTime()+(30*1000));
	cookie='reg8log_autologin2=logout;path=/';
	cookie+=';expires='+exp.toGMTString();
	if(location.protocol=='https:') cookie+=';secure';
	document.cookie=cookie;
	return true;
}
