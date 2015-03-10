<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

require_once ROOT.'include/func/func_autologin_ages.php';
$autologin_ages=get_autologin_ages();

if(isset($_POST['autologin_age'])) {
	if(!in_array($_POST['autologin_age'], $autologin_ages)) {
		$autologin_age_msg=func::tr('illegal autologin_age msg');
		require ROOT.'include/page/page_login_form.php';
		exit;
	}
	$autologin_age=$_POST['autologin_age'];
}
else {
	if(count($autologin_ages)!=1) {
		$autologin_age_msg=func::tr('illegal autologin_age msg');
		require ROOT.'include/page/page_login_form.php';
		exit;
	}
	$autologin_age=$autologin_ages[0];
}

$user->save_identity($autologin_age, false, true);

?>
