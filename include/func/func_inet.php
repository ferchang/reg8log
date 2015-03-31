<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

function inet_pton2($address)
{
	if(function_exists('inet_pton') and (strpos($address, '.')!==false or stripos(php_uname(), 'Windows XP')===false)) return inet_pton($address);
	/* note: IPv6 support not enabled by default on WinXp and causes php inet_pton function to not work so i added the condition stripos(php_uname(), 'Windows XP')===false */
	
	//note: the following php implementation of inet_pton seems to not working correctly with compressed IPv6 addresses like 2001:db8::ff00:42:8329
	
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

//==========================

function inet_ntop2($address) {

	if(function_exists('inet_ntop') and (strlen($address)===4 or stripos(php_uname(), 'Windows XP')===false)) return inet_ntop($address);

    if (strlen($address)===4) {
        // ipv4
        list(,$address)=unpack('N', $address);
        $address=long2ip($address);
		return $address;
    } elseif(strlen($address)===16) {
        // ipv6
        $address=bin2hex($address);
        $address=substr(chunk_split($address,4,':'),0,-1);
        $address=explode(':',$address);
        $res='';
        foreach($address as $seg) {
		/* disabled codes below r apparently related to compressing IPv6 address
		i (hamidreza_mz -=At=- yahoo) disabled compressing in order to make the output work with the PHP implementation of inet_pton that u can see in the inet_pton2 function above */
        /*    while($seg{0}=='0') $seg=substr($seg,1);
            if ($seg!='') {
                $res.=($res==''?'':':').$seg;
            } else {
                if (strpos($res,'::')===false) {
                    if (substr($res,-1)==':') continue;
                    $res.=':';
                    continue;
                }
                $res.=($res==''?'':':').'0';
            }
        }*/
		$res.=($res===''?'':':').$seg;
    }
	
    return $res;
}

}

?>
