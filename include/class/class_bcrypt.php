<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

class bcrypt {
  
  public static function hash($input, $rounds=null) {
  
	if(CRYPT_BLOWFISH != 1) {
      throw new Exception("bcrypt not supported in this installation. See http://php.net/crypt");
    }
	
	if($rounds===null) $rounds=config::get('bcrypt_hash_rounds');
	if($rounds<4) {
		trigger_error('reg8log: class bcrypt: got a rounds count less than 4! Setting it to 4...', E_USER_WARNING);
		$rounds=4;//apparently, bcrypt doesn't support less than 4 rounds
	}
  
    $hash = crypt(hash('sha256', config::get('pepper').$input), self::getSalt($rounds));
	//bcrypt uses only 72 chars of its input! By prepending a 22 char pepper, only 50 chars of password are left. this seems too limited to me (hamidreza.mz712 -=At=- gmail -=Dot=- com), so i decided to use hash('sha256', $pepper.$input) before feeding input to bcrypt to address this limitation.
 
    if(strlen($hash) > 13)
      return $hash;
 
    return false;
	
  }
  
  //--------------------------------------
 
  public static function verify($input, $existingHash) {
  
    $hash = crypt(hash('sha256', config::get('pepper').$input), $existingHash);
 
    return $hash === $existingHash;
	
  }
  
  //--------------------------------------
 
  private static function getSalt($rounds) {
  
    $salt = sprintf('$2a$%02d$', $rounds);
 
    $bytes = func::random_bytes(16);
 
    $salt .= self::encodeBytes($bytes);
	
    return $salt;
	
  }
  
  //--------------------------------------
 
  private static function encodeBytes($input) {
  
    // The following is code from the PHP Password Hashing Framework
    $itoa64 = './ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
 
    $output = '';
    $i = 0;
    do {
      $c1 = ord($input[$i++]);
      $output .= $itoa64[$c1 >> 2];
      $c1 = ($c1 & 0x03) << 4;
      if ($i >= 16) {
        $output .= $itoa64[$c1];
        break;
      }
 
      $c2 = ord($input[$i++]);
      $c1 |= $c2 >> 4;
      $output .= $itoa64[$c1];
      $c1 = ($c2 & 0x0f) << 2;
 
      $c2 = ord($input[$i++]);
      $c1 |= $c2 >> 6;
      $output .= $itoa64[$c1];
      $output .= $itoa64[$c2 & 0x3f];
    } while (1);
 
    return $output;
	
  }
  
  //--------------------------------------
  
}

?>