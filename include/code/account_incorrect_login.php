<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(strtolower($_POST['username'])=='admin') {
	$account_block_threshold=$admin_account_block_threshold;
	$account_captcha_threshold=$admin_account_captcha_threshold;
	$account_block_period=$admin_account_block_period;
	$ip_block_threshold=$admin_ip_block_threshold;
	$ip_captcha_threshold=$admin_ip_captcha_threshold;
	$ip_block_period=$admin_ip_block_period;
}

if($account_block_threshold==-1  and $account_captcha_threshold==-1) return;

require $index_dir.'include/config/config_register.php';

if(!$username_exists and $registeration_enabled and $ajax_check_username and !$max_ajax_check_usernames) {
	$no_pretend_user=true;
	return;
}

require_once $index_dir.'include/code/code_db_object.php';

$_username=$reg8log_db->quote_smart($_POST['username']);

$query="select * from `account_incorrect_logins` where `username`=$_username limit 1";

$reg8log_db->query($query);

$cookie_capacity=30;

if(!$reg8log_db->result_num()) {
	$attempts=$reg8log_db->quote_smart(pack('l10', $req_time, 0, 0, 0, 0, 0, 0, 0, 0, 0));
	$pos=2;
	$field_values="$_username, $username_exists, $attempts, $pos, $req_time";
	$query="insert into `account_incorrect_logins` (`username`, `username_exists`, `attempts`, `pos`, `last_attempt`) values($field_values)";
	$reg8log_db->query($query);

	$insert_id=mysql_insert_id();
	
	if(!isset($_COOKIE['reg8log_incorrect_logins'])) $cookie_contents=$insert_id.','.$req_time;
	else {
		$cookie_contents=$_COOKIE['reg8log_incorrect_logins'].','.$insert_id.','.$req_time;
		$cookie_contents=implode(',', array_slice(explode(',', $cookie_contents), -2*$cookie_capacity));
	}
	setcookie('reg8log_incorrect_logins', $cookie_contents, 0, '/', null, $https, true);
	
	if($account_block_threshold==1) {
		$_username2=$_POST['username'];
		require_once $index_dir.'include/code/code_accomodate_block_disable.php';
		if($block_disable!=2 and $block_disable!=3) {
			$account_block=$_POST['username'];
			$block_duration=$account_block_period;
			$first_attempt=$req_time;
			require_once $index_dir.'include/code/code_log_account_block.php';
		}
		else if($account_captcha_threshold==1) $captcha_needed=true;
	}
	else if($account_captcha_threshold==1) $captcha_needed=true;

	$incorrect_attempts=1;

	return;
}

$rec5=$reg8log_db->fetch_row();

$insert_id=$rec5['auto'];

$attempts = unpack("l10", $rec5['attempts']);

$count=1; //1 for current incorrect attempt
$oldest=$req_time;
foreach($attempts as $value) if(($req_time-$value)<$account_block_period) {
	$count++;
	if($value<$oldest) $oldest=$value;
}

$incorrect_attempts=$count;

if($account_block_threshold!=-1 and $count>=$account_block_threshold) {
	$_username2=$_POST['username'];
	require_once $index_dir.'include/code/code_accomodate_block_disable.php';
	if($block_disable!=2 and $block_disable!=3) {
		$account_block=$_POST['username'];
		$block_duration=$oldest+$account_block_period-$req_time;
		$first_attempt=$oldest;
		require_once $index_dir.'include/code/code_log_account_block.php';
	}
	else if($account_captcha_threshold!=-1 and $count>=$account_captcha_threshold) $captcha_needed=true;
}
else if($account_captcha_threshold!=-1 and $count>=$account_captcha_threshold) $captcha_needed=true;

$pos=$rec5['pos'];

$attempts[$pos]=$req_time;

$attempts=$reg8log_db->quote_smart(pack('l10', $attempts[1], $attempts[2], $attempts[3], $attempts[4], $attempts[5], $attempts[6], $attempts[7], $attempts[8], $attempts[9], $attempts[10]));

$pos++;
if($pos>10) $pos=1;

$query="update `account_incorrect_logins` set `attempts`=$attempts, `pos`=$pos, `last_attempt`=$req_time where `username`=$_username limit 1";

$reg8log_db->query($query);

if(!isset($_COOKIE['reg8log_incorrect_logins'])) $cookie_contents=$insert_id.','.$req_time;
else {
	$cookie_contents=$_COOKIE['reg8log_incorrect_logins'].','.$insert_id.','.$req_time;
	$cookie_contents=implode(',', array_slice(explode(',', $cookie_contents), -2*$cookie_capacity));
}
setcookie('reg8log_incorrect_logins', $cookie_contents, 0, '/', null, $https, true);

require_once $index_dir.'include/config/config_cleanup.php';

if(mt_rand(1, floor(1/$cleanup_probability))==1) {
	$table_name='account_incorrect_logins';
	require $index_dir.'include/code/code_account_incorrect_logins_expired_cleanup.php';
}

if(mt_rand(1, floor(1/$cleanup_probability))==1) {
	$table_name='account_incorrect_logins';
	require $index_dir.'include/code/code_account_incorrect_logins_size_cleanup.php';
}

?>