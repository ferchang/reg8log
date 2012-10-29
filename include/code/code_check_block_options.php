<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

require_once $index_dir.'include/config/config_brute_force_protection.php';

$block_options=array();

if($identified_user=='Admin') {
	
	if($admin_account_block_threshold==-1 and $admin_ip_block_threshold==-1) return;
	
	$block_options['no_block']=0;
	
	if($admin_account_block_threshold!=-1) {
		$block_options['account_block']=2;
		$block_options['no_block']+=2;
	}
	if($admin_ip_block_threshold!=-1) {
		$block_options['ip_block']=1;
		$block_options['no_block']+=1;
	}

	if($admin_account_block_threshold!=-1) $block_options['account_block']=$block_options['no_block']-$block_options['account_block'];
	
	if($admin_ip_block_threshold!=-1) $block_options['ip_block']=$block_options['no_block']-$block_options['ip_block'];
	
	if($block_options['no_block']==3) $block_options['account8ip_block']=0;
	
}
else {
	
	if(!$allow_users2disable_blocks or ($account_block_threshold==-1 and $ip_block_threshold==-1)) return;

	$block_options['no_block']=0;
	
	if(($allow_users2disable_blocks==2 or $allow_users2disable_blocks==3) and $account_block_threshold!=-1) {
		$block_options['account_block']=2;
		$block_options['no_block']=+2;
	}
	if(($allow_users2disable_blocks==1 or $allow_users2disable_blocks==3) and $ip_block_threshold!=-1) {
		$block_options['ip_block']=1;
		$block_options['no_block']+=1;
	}

	if(isset($block_options['account_block'])) $block_options['account_block']=$block_options['no_block']-$block_options['account_block'];
	
	if(isset($block_options['ip_block'])) $block_options['ip_block']=$block_options['no_block']-$block_options['ip_block'];
	
	if($block_options['no_block']==3) $block_options['account8ip_block']=0;
	
	if($account_block_threshold!=-1 and $ip_block_threshold!=-1 and $block_options['no_block']!=3) {
		if(isset($block_options['account_block'])) {
			$block_options['account8ip_block']=0;
			$block_options['ip_block']=$block_options['no_block'];
			unset($block_options['account_block'], $block_options['no_block']);
		}
		else if(isset($block_options['ip_block'])) {
			$block_options['account8ip_block']=0;
			$block_options['account_block']=$block_options['no_block'];
			unset($block_options['ip_block'], $block_options['no_block']);
		}
	}
	
}

?>