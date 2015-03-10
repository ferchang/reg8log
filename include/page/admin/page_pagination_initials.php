<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

define('CAN_INCLUDE', true);

$color1='#aaa';
$color2='#ccc';

if(!isset($page)) return;

if($page*$per_page>$total) $less=($page*$per_page)-$total;
else $less=0;
$first=($page-1)*$per_page+1;
$last=($page*$per_page-$less);
$num=$last-$first+1;

?>

