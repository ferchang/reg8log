<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

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
function tie_login2ip($user_info) {
	global $tie_login2ip_option_at_login;
	global $tie_login2ip;

	if(($tie_login2ip_option_at_login and $user_info['tie_login2ip']) or  (!$tie_login2ip_option_at_login and ($tie_login2ip==3 or ($tie_login2ip==1 and $user_info['username']=='Admin') or ($tie_login2ip==2 and $user_info['username']!='Admin')))) return true;
	else return false;
}
//=======================================
function identify($username=null, $password=null)
{

	global $reg8log_db;
	global $site_key;
	global $site_key2;
	global $parent_page;
	global $change_autologin_key_upon_login;
	global $index_dir;
	global $https;
	global $block_disable;
	$block_disable=0;
	global $last_protection;
	$last_protection=-1;
	global $req_time;
	global $pepper;
	global $tie_login2ip_option_at_login;
	
	$this->err_msg='';
	$this->user_info=null;

	if(!is_null($username)) {//Manual login

		require_once $index_dir.'include/code/code_db_object.php';

		$tmp7=$reg8log_db->quote_smart($username);

		$query1='select * from `accounts` where `username`='.$tmp7.' limit 1';

		require $index_dir.'include/config/config_register.php';
		
		$expired1=$req_time-$email_verification_time;
		$expired2=$req_time-$admin_confirmation_time;

		$query2='select * from `pending_accounts` where `username`='.$tmp7." and (`email_verification_key`='' or `email_verified`=1 or `timestamp`>". $expired1.') and (`admin_confirmed`=1 or `timestamp`>'.$expired2.') limit 1';

		global $username_exists;
		$username_exists=0;

		require_once $index_dir.'include/code/code_fetch_site_vars.php';

		$lock_name=$reg8log_db->quote_smart('reg8log--ban-'.$this->user_info['username']."--$site_key");
		$reg8log_db->query("select get_lock($lock_name, -1)");
		
		if($reg8log_db->result_num($query1)) {
			$username_exists=1;
			$this->user_info=$reg8log_db->fetch_row();
			$block_disable=$this->user_info['block_disable'];
			$last_protection=$this->user_info['last_protection'];
			if(verify_secure_hash($password, $this->user_info['password_hash'])) {
			
				if($tie_login2ip_option_at_login) {
					$login2ip=isset($_POST['login2ip']);
					if($login2ip!=$this->user_info['tie_login2ip']) {
						$tmp55='update `accounts` set `tie_login2ip`='.(($login2ip)? '1':'0').' where `username`='.$tmp7.' limit 1';
						$reg8log_db->query($tmp55);
						$this->user_info['tie_login2ip']=($login2ip)? '1':'0';
					}
				}
			
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
				return true;
				//if(!isset($banned_user)) return true;
				//else $this->user_info=null;
			}

		}
		else {
			$reg8log_db->query("select release_lock($lock_name)");
			if($reg8log_db->result_num($query2)) {
				$username_exists=1;
				global $is_pending_account;
				$is_pending_account=1;
				global $rec;
				$rec=$reg8log_db->fetch_row();
				$this->user_info=$rec;
				if(verify_secure_hash($password, $rec['password_hash'])) {
					global $pending_user;
					$pending_user=$rec['username'];
					return true;
				}
			}
			else {//here we run a verify_secure_hash to prevent information leakage about username existence via timing
				global $secure_hash_rounds;
				verify_secure_hash($password, $secure_hash_rounds.'*111111111111111111111111111111111111111111111111');
				$username_exists=0;
			}
		}

		return false;

	}//Manual login

	//----------------------------------------------------------------------------

	//Following is the cookie auto-login mechanism

	if(!isset($_COOKIE['reg8log_autologin'])) {
		if(isset($_COOKIE['reg8log_autologin2'])) setcookie('reg8log_autologin2', false, mktime(12,0,0,1, 1, 1990), '/', null, $https, true);
		return false;
	}
	
	if(!isset($_COOKIE['reg8log_autologin2'])) {
		setcookie('reg8log_autologin', false, mktime(12,0,0,1, 1, 1990), '/', null, $https, true);
		return false;
	}

	$cookie=new hm_cookie('reg8log_autologin', $this->identify_structs['autologin_cookie']['value_seperator']);
	$cookie->secure=$https;

	if($cookie->get()) {//cookie read successfully

		require_once $index_dir.'include/code/code_db_object.php';

		$query='select * from `accounts` where ';
		foreach($this->identify_structs['autologin_cookie'] as $key=>$value) if(is_int($key)) {
				if($value=='autologin_key') {
					$autologin_key=$cookie->values[$key];
					continue;
				}
				if($key) $query.=' and ';
				$query.="`$value`".'='.$reg8log_db->quote_smart($cookie->values[$key]);
			}
		$query.=' limit 1';
		$flag=false;
		if($reg8log_db->result_num($query)) {
			$tmp54=$reg8log_db->fetch_row();
			if($this->tie_login2ip($tmp54)) {
				if(hash('sha256', $tmp54['autologin_key'].$_SERVER['REMOTE_ADDR'])==$autologin_key) $flag=true;
			}
			else if($tmp54['autologin_key']==$autologin_key) $flag=true;
		}

	}//cookie read successfully
	else {
		if($cookie->err_msg) $this->error($cookie->err_msg);
		return false;
	}

	require_once $index_dir.'include/code/code_fetch_site_vars.php';

	$lock_name=$reg8log_db->quote_smart('reg8log--ban-'.$this->user_info['username']."--$site_key");
	$reg8log_db->query("select get_lock($lock_name, -1)");
	
	if($flag) {
		if($this->tie_login2ip($tmp54)) $autologin_key=hash('sha256', $tmp54['autologin_key'].$_SERVER['REMOTE_ADDR']);
		else $autologin_key=$tmp54['autologin_key'];
		$this->user_info=$tmp54;
		if($_COOKIE['reg8log_autologin2']=='logout' or $_COOKIE['reg8log_autologin2']!=hash('sha256', $pepper.$site_key2.$autologin_key)) {
			global $logged_out_user;
			$logged_out_user=$this->user_info['username'];
			$cookie->erase();
			setcookie('reg8log_autologin2', false, mktime(12,0,0,1, 1, 1990), '/', null, $https);
			return false;
		}
		$block_disable=$this->user_info['block_disable'];
		$last_protection=$this->user_info['last_protection'];
		$this->autologin_durability=($cookie->values[$key+1])? 'permanent' : 'session';
		if($change_autologin_key_upon_login==2) {
			$new_autologin_key=random_string(43);
			$query="update `accounts` set `autologin_key`='".$new_autologin_key."' where `username`=".$reg8log_db->quote_smart($this->user_info['username']).' limit 1';
			$reg8log_db->query($query);
			$this->user_info['autologin_key']=$new_autologin_key;
			if($cookie->values[$key+1]) $this->save_identity($cookie->values[$key+1], true);
			else $this->save_identity($this->autologin_durability);
		}
		if($this->user_info['banned']) {
			$_username=$this->user_info['username'];
			$_until=$this->user_info['banned'];
			require $index_dir.'include/code/code_check_ban_status.php';
		}
		$reg8log_db->query("select release_lock($lock_name)");
		return true;
	}

	$reg8log_db->query("select release_lock($lock_name)");
	
	$cookie->erase();//erase auto-login cookie in case of the user is not authenticated with it.
	setcookie('reg8log_autologin2', false, mktime(12,0,0,1, 1, 1990), '/', null, $https, true);

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
	setcookie('reg8log_autologin2', false, mktime(12,0,0,1, 1, 1990), '/', null, $https);

	if(isset($_COOKIE['reg8log_session'])) {//session cookie exists
		require $index_dir.'include/code/sess/code_sess_start.php';
		require $index_dir.'include/code/sess/code_sess_destroy.php';
	}//session cookie exists

	if($this->err_msg) return false;

	return true;
	
}//end of logout
//=======================================
function save_identity($age='session', $is_abs_time=false)
{

	global $parent_page;
	global $https;
	global $req_time;
	global $site_key2;
	global $pepper;

	$this->err_msg='';

	if(is_null($this->user_info)) {
		$this->error('No user information to save');
		return false;
	}

	if($age==='permanent') $age=$this->identify_structs['autologin_cookie']['long_age'];
	else if($age!=='session' and !(is_numeric($age) and $age > 0)) {
			$this->error("Invalid age value '$age'");
			return false;
		}

	$cookie=new hm_cookie('reg8log_autologin', $this->identify_structs['autologin_cookie']['value_seperator']);
	$cookie->secure=$https;
	$autologin_key=$this->user_info['autologin_key'];
	foreach($this->identify_structs['autologin_cookie'] as $key=>$value) if(is_int($key)) {
		if($value=='autologin_key' and $this->tie_login2ip($this->user_info)) {
			$autologin_key=hash('sha256', $this->user_info['autologin_key'].$_SERVER['REMOTE_ADDR']);
			$cookie->values[]=$autologin_key;
		}
		else $cookie->values[]=$this->user_info[$value];
	}

	$cookie->values[]=(($age=='session')? 0 : (($is_abs_time)? $age : $age+$req_time));

	if($cookie->set(null, $cookie->values, null, $age, $is_abs_time)) {
		setcookie('reg8log_autologin2', hash('sha256', $pepper.$site_key2.$autologin_key), (($age=='session')? 0 : (($is_abs_time)? $age : $age+$req_time)), '/', null, $https);
		return true;
	}

	if($cookie->err_msg) $this->error($cookie->err_msg);

	return false;

}
//=======================================

}//end of user class

?>
