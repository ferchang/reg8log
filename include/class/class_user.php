<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

class hm_user {

var $err_msg='';
var $user_info=null;
var $identify_structs=null;
var $autologin_cookie_expiration='';

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

	if((config::get('tie_login2ip_option_at_login') and $user_info['tie_login2ip']) or  (!config::get('tie_login2ip_option_at_login') and (config::get('tie_login2ip')===3 or (config::get('tie_login2ip')===1 and $user_info['username']==='Admin') or (config::get('tie_login2ip')===2 and $user_info['username']!=='Admin')))) return true;
	else return false;
}
//=======================================
function identify($username=null, $password=null)
{

	$GLOBALS['block_disable']=0;
	$GLOBALS['last_protection']=-1;
	
	$this->err_msg='';
	$this->user_info=null;

	if(!is_null($username)) {//Manual login

		$tmp7=$GLOBALS['reg8log_db']->quote_smart($username);

		$query1='select * from `accounts` where `username`='.$tmp7.' limit 1';

		$expired1=$GLOBALS['req_time']-config::get('email_verification_time');
		$expired2=$GLOBALS['req_time']-config::get('admin_confirmation_time');

		$query2='select * from `pending_accounts` where `username`='.$tmp7." and (`email_verification_key`='' or `email_verified`=1 or `timestamp`>". $expired1.') and (`admin_confirmed`=1 or `timestamp`>'.$expired2.') limit 1';

		$GLOBALS['username_exists']=0;

		$lock_name=$GLOBALS['reg8log_db']->quote_smart('reg8log--ban-'.$this->user_info['username'].'--'.SITE_KEY);
		$GLOBALS['reg8log_db']->query("select get_lock($lock_name, -1)");
		
		if($GLOBALS['reg8log_db']->result_num($query1)) {
			$GLOBALS['username_exists']=1;
			$this->user_info=$GLOBALS['reg8log_db']->fetch_row();
			$GLOBALS['block_disable']=$this->user_info['block_disable'];
			$GLOBALS['last_protection']=$this->user_info['last_protection'];
			if(bcrypt::verify($password, $this->user_info['password_hash'])) {
			
				if(config::get('tie_login2ip_option_at_login')) {
					$login2ip=isset($_POST['login2ip']);
					if($login2ip!=$this->user_info['tie_login2ip']) {
						$tmp55='update `accounts` set `tie_login2ip`='.(($login2ip)? '1':'0').' where `username`='.$tmp7.' limit 1';
						$GLOBALS['reg8log_db']->query($tmp55);
						$this->user_info['tie_login2ip']=($login2ip)? '1':'0';
					}
				}
				
				if($this->user_info['username']==='Admin') config::set('change_autologin_key_upon_login', config::get('admin_change_autologin_key_upon_login'));
				if(config::get('change_autologin_key_upon_login')) {
					$new_autologin_key=func::random_string(43);
					$query="update `accounts` set `autologin_key`='".$new_autologin_key."' where `username`=".$tmp7.' limit 1';
					$GLOBALS['reg8log_db']->query($query);
					$this->user_info['autologin_key']=$new_autologin_key;
				}
				if($this->user_info['banned']) {
					$_username=$this->user_info['username'];
					$_until=$this->user_info['banned'];
					require ROOT.'include/code/code_check_ban_status.php';
				}
				$GLOBALS['reg8log_db']->query("select release_lock($lock_name)");
				return true;
				//if(!isset($banned_user)) return true;
				//else $this->user_info=null;
			}

		}
		else {
			$GLOBALS['reg8log_db']->query("select release_lock($lock_name)");
			if($GLOBALS['reg8log_db']->result_num($query2)) {
				$GLOBALS['username_exists']=1;
				$GLOBALS['is_pending_account']=1;
				$GLOBALS['rec']=$GLOBALS['reg8log_db']->fetch_row();
				$this->user_info=$GLOBALS['rec'];
				if(bcrypt::verify($password, $GLOBALS['rec']['password_hash'])) {
					$GLOBALS['pending_user']=$GLOBALS['rec']['username'];
					return true;
				}
			}
			else {//here we run a bcrypt::verify to prevent information leakage about username existence via timing
				bcrypt::verify($password, '$2a$09$hyMbcpbP6hnjcm9BJBEJ6OxPVNdTiq1HImfK4hx4Rjlb65Ylk1wOS');
				$GLOBALS['username_exists']=0;
			}
		}

		return false;

	}//Manual login

	//----------------------------------------------------------------------------

	//Following is the cookie auto-login mechanism

	if(!isset($_COOKIE['reg8log_autologin'])) {
		if(isset($_COOKIE['reg8log_autologin2'])) setcookie('reg8log_autologin2', false, mktime(12,0,0,1, 1, 1990), '/', null, HTTPS, true);
		return false;
	}
	
	if(!isset($_COOKIE['reg8log_autologin2'])) {
		setcookie('reg8log_autologin', false, mktime(12,0,0,1, 1, 1990), '/', null, HTTPS, true);
		return false;
	}

	$cookie=new hm_cookie('reg8log_autologin', $this->identify_structs['autologin_cookie']['value_seperator']);
	$cookie->secure=HTTPS;

	if($cookie->get()) {//cookie read successfully

		$query='select * from `accounts` where ';
		foreach($this->identify_structs['autologin_cookie'] as $key=>$value) if(is_int($key)) {
				if($value==='autologin_key') {
					$autologin_key=$cookie->values[$key];
					continue;
				}
				if($key) $query.=' and ';
				$query.="`$value`".'='.$GLOBALS['reg8log_db']->quote_smart($cookie->values[$key]);
			}
		$query.=' limit 1';
		$flag=false;
		if($GLOBALS['reg8log_db']->result_num($query)) {
			$tmp54=$GLOBALS['reg8log_db']->fetch_row();
			if($this->tie_login2ip($tmp54)) {
				if(hash('sha256', $tmp54['autologin_key'].$_SERVER['REMOTE_ADDR'])===$autologin_key) $flag=true;
			}
			else if($tmp54['autologin_key']===$autologin_key) $flag=true;
			
			if($tmp54['username']==='Admin') config::set('change_autologin_key_upon_login', config::get('admin_change_autologin_key_upon_login'));
			if($flag) do {
				if(!config::get('change_autologin_key_upon_login')) {
					if(config::get('dont_enforce_autoloign_age_sever_side_when_change_autologin_key_upon_login_is_zero')===3) break;
					if(config::get('dont_enforce_autoloign_age_sever_side_when_change_autologin_key_upon_login_is_zero')===2 and $tmp54['username']!=='Admin') break;
					if(config::get('dont_enforce_autoloign_age_sever_side_when_change_autologin_key_upon_login_is_zero')===1 and $tmp54['username']==='Admin') break;
				}
				if($tmp54['autologin_expiration'] and $tmp54['autologin_expiration']<$GLOBALS['req_time']) $flag=false;
			} while(false);
			
		}

	}//cookie read successfully
	else {
		if($cookie->err_msg) $this->error($cookie->err_msg);
		return false;
	}

	$lock_name=$GLOBALS['reg8log_db']->quote_smart('reg8log--ban-'.$this->user_info['username'].'--'.SITE_KEY);
	$GLOBALS['reg8log_db']->query("select get_lock($lock_name, -1)");
	
	if($flag) {
		if($this->tie_login2ip($tmp54)) $autologin_key=hash('sha256', $tmp54['autologin_key'].$_SERVER['REMOTE_ADDR']);
		else $autologin_key=$tmp54['autologin_key'];
		$this->user_info=$tmp54;
		if($_COOKIE['reg8log_autologin2']==='logout' or $_COOKIE['reg8log_autologin2']!==hash('sha256', config::get('pepper').SITE_KEY2.$autologin_key)) {
			$GLOBALS['logged_out_user']=$this->user_info['username'];
			$cookie->erase();
			setcookie('reg8log_autologin2', false, mktime(12,0,0,1, 1, 1990), '/', null, HTTPS);
			return false;
		}
		$GLOBALS['block_disable']=$this->user_info['block_disable'];
		$GLOBALS['last_protection']=$this->user_info['last_protection'];
		if(!is_numeric($cookie->values[$key+1])) exit("<center><h3>Error: expiration time in cookie is not numeric!</h3></center>");
		$this->autologin_cookie_expiration=$cookie->values[$key+1];
		if($this->user_info['username']==='Admin') config::set('change_autologin_key_upon_login', config::get('admin_change_autologin_key_upon_login'));
		if(config::get('change_autologin_key_upon_login')===2) {
			$new_autologin_key=func::random_string(43);
			$query="update `accounts` set `autologin_key`='".$new_autologin_key."' where `auto`=".$this->user_info['auto'].' limit 1';
			$GLOBALS['reg8log_db']->query($query);
			$this->user_info['autologin_key']=$new_autologin_key;
			$this->save_identity($this->autologin_cookie_expiration, true);
		}
		if($this->user_info['banned']) {
			$_username=$this->user_info['username'];
			$_until=$this->user_info['banned'];
			require ROOT.'include/code/code_check_ban_status.php';
		}
		$GLOBALS['reg8log_db']->query("select release_lock($lock_name)");
		return true;
	}

	$GLOBALS['reg8log_db']->query("select release_lock($lock_name)");
	
	$cookie->erase();//erase auto-login cookie in case of the user is not authenticated with it.
	setcookie('reg8log_autologin2', false, mktime(12,0,0,1, 1, 1990), '/', null, HTTPS, true);

	return false;

}//end of identify method

//=======================================
function logout()
{

	$this->err_msg='';

	setcookie('reg8log_autologin', false, mktime(12,0,0,1, 1, 1990), '/', null, HTTPS, true);
	setcookie('reg8log_autologin2', false, mktime(12,0,0,1, 1, 1990), '/', null, HTTPS);

	if($this->err_msg) return false;

	return true;
	
}//end of logout
//=======================================
function save_identity($age, $is_abs_time=false, $set_autologin_expiration=false)
{
	
	$this->err_msg='';

	if(is_null($this->user_info)) {
		$this->error('No user information to save');
		return false;
	}

	$cookie=new hm_cookie('reg8log_autologin', $this->identify_structs['autologin_cookie']['value_seperator']);
	$cookie->secure=HTTPS;
	$autologin_key=$this->user_info['autologin_key'];
	foreach($this->identify_structs['autologin_cookie'] as $key=>$value) if(is_int($key)) {
		if($value==='autologin_key' and $this->tie_login2ip($this->user_info)) {
			$autologin_key=hash('sha256', $this->user_info['autologin_key'].$_SERVER['REMOTE_ADDR']);
			$cookie->values[]=$autologin_key;
		}
		else $cookie->values[]=$this->user_info[$value];
	}

	$age=(($is_abs_time)? $age : (($age)? $age+$GLOBALS['req_time'] : 0));
	
	$cookie->values[]=$age;
	
	if($cookie->set(null, $cookie->values, null, $age, true)) {
		setcookie('reg8log_autologin2', hash('sha256', config::get('pepper').SITE_KEY2.$autologin_key), $age, '/', null, HTTPS);

	//----------------
	if($set_autologin_expiration) {
		if(!$age) {
			if(config::get('max_session_autologin_age')) $autologin_expiration=$GLOBALS['req_time']+config::get('max_session_autologin_age');
			else $autologin_expiration=0;
		}
		else $autologin_expiration=$age;
		$query="update `accounts` set `autologin_expiration`=".$autologin_expiration." where `auto`=".$this->user_info['auto'].' limit 1';
		$GLOBALS['reg8log_db']->query($query);
	}
	//----------------
		
		return true;
	}

	if($cookie->err_msg) $this->error($cookie->err_msg);

	return false;

}
//=======================================

}//end of user class

?>
