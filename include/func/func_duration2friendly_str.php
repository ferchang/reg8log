<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

function duration2friendly_str($t, $c=2, $abb=false) {

	if($c===0) $c=100;

	$y=$mth=$d=$h=$m=$s=0;

	$y=(int)floor($t/(365*24*60*60));
	if($y) $t=$t%(365*24*60*60);
	$mth=(int)floor($t/(30*24*60*60));
	if($mth) $t=$t%(30*24*60*60);
	$d=(int)floor($t/(24*60*60));
	if($d) $t=$t%(24*60*60);
	$h=(int)floor($t/(60*60));
	if($h) $t=$t%(60*60);
	$m=(int)floor($t/60);
	$s=$t%60;

//---------------------------

//echo "<br>$y-$mth-$d-$h-$m-$s<br>";

	$n=0;
	$f=false;

	do {

		if($y and $n+1===$c and $mth>=6) {
			$y++;
			$mth=0;
		}

		if($y) $f=true;

		if($f and ++$n===$c) break;

		if(($f or $mth) and $n+1===$c and $d>=15) {
			$mth++;
			$d=0;
		}

		if($mth) $f=true;
		
		if($f and ++$n===$c) break;
		
		if(($f or $d) and $n+1===$c and $h>=12) {
			$d++;
			$h=0;
		}

		if($d) $f=true;

		if($f and ++$n===$c) break;

		if(($f or $h) and $n+1===$c and $m>=30) {
			$h++;
			$m=0;
		}
		
		if($h) $f=true;

		if($f and ++$n===$c) break;

		if(($f or $m) and $n+1===$c and $s>=30) {
			$m++;
			$s=0;
		}

	} while(false);

//echo "$y-$mth-$d-$h-$m-$s<br>";
	
//---------------------------

while($mth===12 or $d===30 or $h===24 or $m===60) {
	if($mth===12) {
		$y++;
		$mth=0;
	}
	if($d===30) {
		$mth++;
		$d=0;
	}
	if($h===24) {
		$d++;
		$h=0;
	}
	if($m===60) {
		$h++;
		$m=0;
	}
}

//---------------------------

	$n=0;
	$msg='';
	if($c<6) $prep=func::tr('about').' ';
	else $prep='';

	do {

		if($y) {
			$msg.="$y ".func::tr('year');
			if($y>1) $msg.=func::tr('s');
		}

		if($msg and ++$n===$c) break;

		if($mth) {
			if($y) $msg.=" ".func::tr('&')." $mth ".func::tr('month');
			else $msg="$mth ".func::tr('month');
			if($mth>1) $msg.=func::tr('s');
		}
		
		if($msg and ++$n===$c) break;
		
		if($d) {
			if($y or $mth) $msg.=" ".func::tr('&')." $d ".func::tr('day');
			else $msg="$d ".func::tr('day');
			if($d>1) $msg.=func::tr('s');
		}

		if($msg and ++$n===$c) break;

		if($h) {
			if($y or $mth or $d) $msg.=" ".func::tr('&')." $h ".func::tr('hour');
			else $msg="$h ".func::tr('hour');
			if($h>1) $msg.=func::tr('s');
		}

		if($msg and ++$n===$c) break;

		if($m) {
			if($y or $mth or $d or $h) $msg.=($abb)? " ".func::tr('&')." $m ".func::tr('min'):" ".func::tr('&')." $m ".func::tr('minute');
			else $msg=($abb)? "$m ".func::tr('min'):"$m ".func::tr('minute');
			if($m>1) $msg.=func::tr('s');
		}

		if($msg and ++$n===$c) break;

		if($s) {
			if($y or $mth or $d or $h or $m) $msg.=($abb)? " ".func::tr('&')." $s ".func::tr('sec'):" ".func::tr('&')." $s ".func::tr('second');
			else $msg=($abb)? "$s ".func::tr('sec'):"$s ".func::tr('second');
			if($t>1) $msg.=func::tr('s');
		}
		
	} while(false);

//---------------------------

	if($msg==='1 day') $msg='24 hours';
	else if($msg==='2 days') $msg='48 hours';
	else if($msg==='1 روز') $msg='24 ساعت';
	else if($msg==='2 روز') $msg='48 ساعت';

	if(config::get('lang')==='fa') {
		$en_num = array('0','1','2','3','4','5','6','7','8','9');
		$fa_num = array('۰','۱','۲','۳','۴','۵','۶','۷','۸','۹');
		$msg=str_replace($en_num, $fa_num, $msg);
	}
	
	return $prep.$msg;

}

?>