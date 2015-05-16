<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

require ROOT.'include/code/code_calc_protection.php';

$tmp35=$block_disable;

if(($protection>$last_protection and !($protection>=($last_protection+2))) or $protection<$last_protection)
	switch($block_disable) {
		case 3:
			if(config::get('ip_block_threshold')!==-1) $block_disable=2;
			else if(config::get('account_block_threshold')!==-1) $block_disable=1;
		break;
		case 2:
			if(config::get('account_block_threshold')!==-1) $block_disable=1;
		break;
		case 1:
			if(config::get('ip_block_threshold')!==-1) $block_disable=0;
		break;
	}

if($block_disable!=$tmp35) {
	$query='update `accounts` set `block_disable`='.intval($block_disable).' where `username`='.$GLOBALS['reg8log_db']->quote_smart($_username2).' limit 1';
	$GLOBALS['reg8log_db']->query($query);
}

?>
