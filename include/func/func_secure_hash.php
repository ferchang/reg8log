<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(!isset($index_dir)) $index_dir='';

require_once $index_dir.'include/func/func_random.php';

require_once $index_dir.'include/info/info_crypto.php';

//==================================

function create_secure_hash($password, $rounds=null) {

	global $secure_hash_rounds;

	if(is_null($rounds)) $rounds=$secure_hash_rounds;
	
	$salt=random_bytes(16);
	//16 bytes = 128 bits -- note that our salt is a binary salt

	global $pepper;

	$hash=hash('sha256', $pepper.$salt.$password, true);

	$tmp17=pow(2, $rounds)-1;

	while($tmp17--) $hash=hash('sha256', $hash.$password, true);

	return $rounds.'*'.$salt.$hash;

}

//==================================

function verify_secure_hash($password, $hash) {

	$rounds=substr($hash, 0, strpos($hash, '*'));
	
	$salt=substr($hash, strpos($hash, '*')+1, strlen($hash)-strlen($rounds)-32-1);

	$hash=substr($hash, strlen($hash)-32);

	global $pepper;

	$tmp17=$hash;

	$hash=hash('sha256', $pepper.$salt.$password, true);

	$rounds=pow(2, $rounds)-1;

	while($rounds--) $hash=hash('sha256', $hash.$password, true);

	return $tmp17===$hash;

}

?>
