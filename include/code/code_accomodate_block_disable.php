<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

require $index_dir.'include/code/code_calc_protection.php';

//echo '<hr>', $last_protection, ' ', $protection, ' ',$block_disable, '<hr>';

$tmp35=$block_disable;

if(($protection>$last_protection and !($protection>=($last_protection+2))) or $protection<$last_protection)
	switch($block_disable) {
		case 3:
			if($ip_lockdown_threshold!=-1) $block_disable=2;
			else if($lockdown_threshold!=-1) $block_disable=1;
		break;
		case 2:
			if($lockdown_threshold!=-1) $block_disable=1;
		break;
		case 1:
			if($ip_lockdown_threshold!=-1) $block_disable=0;
		break;
	}

if($block_disable!=$tmp35) {
	//echo 'block disable update';
	$query='update `accounts` set `block_disable`='.$block_disable.' where `username`='.$reg8log_db->quote_smart($_username2).' limit 1';
	$reg8log_db->query($query);
}

//echo '<hr>', $block_disable, '<hr>';

?>
