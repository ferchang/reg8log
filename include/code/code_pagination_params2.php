<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(!isset($index_dir)) $index_dir='';

if(($page-1)*$per_page>=$total) {
	$tmp27=floor($total/$per_page);
	if($tmp27*$per_page<$total) $page=$tmp27+1;
	else $page=$tmp27;
	header("Location: ?per_page=$per_page&page=$page&sort_by=$sort_by&sort_dir=$sort_dir");
	exit;
}

$offset=($page-1)*$per_page;

?>