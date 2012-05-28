<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(!isset($index_dir)) $index_dir='';

class hm_user {

var $err_msg='';
var $user_info=null;
var $identify_structs=null;
var $autologin_durability='';

//=======================================
function hm_user($identify_structs)
{
	$this->identify_structs=$identify_structs;
}
//=======================================
function error($err_msg='')
{
	$this->err_msg=get_class($this).": $err_msg";
}
//=======================================
function identify($username=null, $password=null)
{

	global $reg8log_db;
	global $site_key;
	global $parent_page;
	global $change_autologin_key_upon_login;
	global $index_dir;
	global $https;

	$this->err_msg='';
	$this->user_info=null;

	if(!is_null($username)) {//Manual login

		require_once $index_dir.'include/code/code_db_object.php';

		$tmp7=$reg8log_db->quote_smart($username);

		$query1='select * from `accounts` where `username`='.$tmp7.' limit 1';

		require $index_dir.'include/info/info_register.php';
		
		$expired1=time()-$email_verification_time;
		$expired2=time()-$admin_confirmation_time;

		$query2='select * from `pending_accounts` where `username`='.$tmp7." and (`email_verification_key`='' or `email_verified`=1 or `timestamp`>". $expired1.') and (`admin_confirmed`=1 or `timestamp`>'.$expired2.') limit 1';

		global $username_exists;

		require_once $index_dir.'include/code/code_fetch_site_vars.php';

		$lock_name=$reg8log_db->quote_smart('reg8log--ban-'.$this->user_info['username']."--$site_key");
		$reg8log_db->query("select get_lock($lock_name, -1)");
		
		if($reg8log_db->result_num($query1)) {
			$username_exists=1;
			$this->user_info=$reg8log_db->fetch_row();
			if(verify_secure_hash($password, $this->user_info['password_hash'])) {
				if($change_autologin_key_upon_login) {
					$new_autologin_key=random_string(43);
					$query="update `accounts` set `autologin_key`='".$new_autologin_key."' where `username`=".$tmp7.' limit 1';
					$reg8log_db->query($query);
					$this->user_info['autologin_key']=$new_autologin_key;
				}
				if($this->user_info['banned']) {
					$_username=$this->user_info['username'];
					$_until=$this->user_info['banned'];
					require $index_dir.'include/code/code_check_ban_status.php';
				}
				$reg8log_db->query("select release_lock($lock_name)");
				if(!isset($banned_user)) return true;
				else $this->user_info=null;
			}
			else $this->user_info=null;
		}
		else {
			$reg8log_db->query("select release_lock($lock_name)");
			if($reg8log_db->result_num($query2)) {
				$username_exists=1;
				global $rec;
				$rec=$reg8log_db->fetch_row();
				if(verify_secure_hash($password, $rec['password_hash'])) {
					global $pending_user;
					$pending_user=$rec['username'];
					return true;
				}
			}
			else {
				require $index_dir.'include/info/info_crypto.php';
				verify_secure_hash($password, $secure_hash_rounds.'*111111111111111111111111111111111111111111111111');
				$username_exists=0;
			}
		}

		return false;

	}//Manual login

	//----------------------------------------------------------------------------

	//Following is the cookie auto-login mechanism

	if(!isset($_COOKIE['reg8log_autologin'])) return false;

	$cookie=new hm_cookie('reg8log_autologin', $this->identify_structs['autologin_cookie']['value_seperator']);
	$cookie->secure=$https;

	if($cookie->get()) {//cookie read successfully

		require_once $index_dir.'include/code/code_db_object.php';

		$query='select * from `accounts` where `';
		foreach($this->identify_structs['autologin_cookie'] as $key=>$value) if(is_int($key)) {
				if($key) $query.=' and `';
				$query.=$this->identify_structs['autologin_cookie'][$key].'`='.$reg8log_db->quote_smart($cookie->values[$key]);
			}
		$query.=' limit 1';

	}//cookie read successfully
	else {
		if($cookie->err_msg) $this->error($cookie->err_msg);
		return false;
	}

	require_once $index_dir.'include/code/code_fetch_site_vars.php';

	$lock_name=$reg8log_db->quote_smart('reg8log--ban-'.$this->user_info['username']."--$site_key");
	$reg8log_db->query("select get_lock($lock_name, -1)");
	
	if($reg8log_db->result_num($query)) {
		$this->user_info=$reg8log_db->fetch_row();
		$this->autologin_durability=($cookie->values[$key+1])? 'permanent' : 'session';
		if($change_autologin_key_upon_login===2) {
			$new_autologin_key=random_string(43);
			$query="update `accounts` set `autologin_key`='".$new_autologin_key."' where `username`=".$reg8log_db->quote_smart($this->user_info['username']).' limit 1';
			$reg8log_db->query($query);
			$this->user_info['autologin_key']=$new_autologin_key;
			$this->save_identity($this->autologin_durability);
		}
		if($this->user_info['banned']) {
			$_username=$this->user_info['username'];
			$_until=$this->user_info['banned'];
			require $index_dir.'include/code/code_check_ban_status.php';
		}
		$reg8log_db->query("select release_lock($lock_name)");
		if(!isset($banned_user)) return true;
		else $this->user_info=null;
	}

	$reg8log_db->query("select release_lock($lock_name)");
	
	$cookie->erase();//erase auto-login cookie in case of the user is not authenticated with it.

	return false;

}//end of identify method

//=======================================
function logout()
{

	global $parent_page;
	global $index_dir;
	global $https;

	$this->err_msg='';

	setcookie('reg8log_autologin', false, mktime(12,0,0,1, 1, 1990), '/', null, $https, true);

	if(isset($_COOKIE['reg8log_session'])) {//session cookie exists
		require $index_dir.'include/code/code_sess_start.php';
		require $index_dir.'include/code/code_sess_destroy.php';
	}//session cookie exists

	if($this->err_msg) return false;

	return true;
	
}//end of logout
//=======================================
function save_identity($age='session')
{

	global $parent_page;
	global $https;

	$this->err_msg='';

	if(is_null($this->user_info)) {
		$this->error('No user information to save');
		return false;
	}

	if($age==='permanent') $age=$this->identify_structs['autologin_cookie']['long_age'];
	else if($age!=='session' and !(is_int($age) and $age > 0)) {
			$this->error("Invalid age value '$age'");
			return false;
		}

	$cookie=new hm_cookie('reg8log_autologin', $this->identify_structs['autologin_cookie']['value_seperator']);
	$cookie->secure=$https;
	foreach($this->identify_structs['autologin_cookie'] as $key=>$value) if(is_int($key)) $cookie->values[]=$this->user_info[$value];

	$cookie->values[]=($age=='session')? 0 : $age+time();

	if($cookie->set(null, $cookie->values, null, $age)) return true;

	if($cookie->err_msg) $this->error($cookie->err_msg);

	return false;

}
//=======================================

}//end of user class

?>
