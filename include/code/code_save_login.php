<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

require_once $index_dir.'include/func/func_autologin_ages.php';
$autologin_ages=get_autologin_ages();

if(isset($_POST['autologin_age'])) {
	if(!in_array($_POST['autologin_age'], $autologin_ages)) {
		$autologin_age_msg=tr('illegal autologin_age msg');
		require $index_dir.'include/page/page_login_form.php';
		exit;
	}
	$autologin_age=$_POST['autologin_age'];
}
else {
	if(count($autologin_ages)!=1) {
		$autologin_age_msg=tr('illegal autologin_age msg');
		require $index_dir.'include/page/page_login_form.php';
		exit;
	}
	$autologin_age=$autologin_ages[0];
}

$user->save_identity($autologin_age, false, true);

?>
