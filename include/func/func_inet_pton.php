<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");



function inet_pton2($address)
{
	if( function_exists('inet_pton') ) {
	  return inet_pton($address);
	} else {
	  // Compat
	  if( false !== strpos($address, ':') ) {
		// IPv6
		$ip = explode(':', $address);
		$res = str_pad('', (4 * (8 - count($ip))), '0000', STR_PAD_LEFT);
		foreach( $ip as $seg ) {
			$res .= str_pad($seg, 4, '0', STR_PAD_LEFT);
		}
		return pack('H' . strlen($res), $res);
	  } else if( false !== strpos($address, '.') ) {
		// IPv4
		return pack('N', ip2long($address));
	  } else {
		// Unknown
		return false;
	  }
	}
}

?>
