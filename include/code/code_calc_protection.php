<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

$possible_protection=0;
if($ip_captcha_threshold!=-1) $possible_protection+=2;
if($account_captcha_threshold!=-1) $possible_protection+=4;
if($ip_block_threshold!=-1) if($account_block_threshold!=-1) $possible_protection+=8;
else $possible_protection+=7;
if($account_block_threshold!=-1) $possible_protection+=15;

//echo $possible_protection;

$protection=$possible_protection;

if($block_disable) switch($block_disable) {
	case 1:
		if($ip_block_threshold!=-1) {
			if($account_block_threshold!=-1) $protection-=8;
			else $protection-=7;
		}
	break;
	case 2:
		if($account_block_threshold!=-1) $protection-=15;
	break;
	case 3:
		if($account_block_threshold!=-1) {
			$protection-=15;
			if($ip_block_threshold!=-1) $protection-=8;
		}
		else if($ip_block_threshold!=-1) $protection-=7;
	break;
}

//echo ' - ', $protection;

?>
