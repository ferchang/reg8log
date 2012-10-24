<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

require $index_dir.'include/code/code_calc_protection.php';

//echo '<hr>', $last_protection, ' ', $protection, ' ',$block_disable, '<hr>';

if(($protection>$last_protection and !($protection>=($last_protection+2))) or $protection<$last_protection) {
	if($block_disable==3) $block_disable=($ip_lockdown_threshold!=-1)? 2 : 1;
	else if($block_disable==2) $block_disable=($lockdown_threshold!=-1)? 1 : 0;
	else if($block_disable==1) $block_disable=0;
}

//echo '<hr>', $block_disable, '<hr>';

?>
