<?phpif(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");if($total>$per_page) {	echo '<br>';	$last=ceil($total/$per_page);	if($last<=$max_page_links) {		for($i=1; $i<=$last; $i++) {			echo '<a class="page" href="?per_page=', $per_page, '&page=', $i, "&sort_by=$sort_by&sort_dir=$sort_dir", '" style="';			if($page==$i) echo ' background: #fff; color: #000; border: 1px solid #000';			echo '">', $i, '</a>&nbsp;&nbsp;';		}	}	else {		echo '<a class="page" href="?per_page=', $per_page, '&page=', 1,  "&sort_by=$sort_by&sort_dir=$sort_dir", '" style="';		if($page==1) echo ' background: #fff; color: #000; border: 1px solid #000';		echo '">', 1, '</a>';				$middle_page_links=$max_page_links-3;		if($middle_page_links%2) $before=$after=floor($middle_page_links/2);		else {			$before=ceil($middle_page_links/2);			$after=$before-1;		}		if($page+$after>=$last) {			$end=$last-1;			$start=$end-$middle_page_links+1;		}		else if($page-$before<=2) {			$start=2;			$end=$start+$middle_page_links-1;		}		else {			$start=$page-$before;			$end=$start+$middle_page_links-1;		}				if($start>2) echo '<b>...</b>';		else echo '&nbsp;&nbsp;';				for($i=$start; $i<=$end; $i++) {			echo '<a class="page" href="?per_page=', $per_page, '&page=', $i, "&sort_by=$sort_by&sort_dir=$sort_dir", '" style="';			if($page==$i) echo ' background: #fff; color: #000; border: 1px solid #000';			echo '">', $i, '</a>';			if($i<$end) echo '&nbsp;&nbsp';		}				if($end+1!=$last) echo '<b>...</b>';		else echo '&nbsp;&nbsp;'; 				echo '<a class="page" href="?per_page=', $per_page, '&page=', $last, "&sort_by=$sort_by&sort_dir=$sort_dir", '" style="';		if($page==$last) echo ' background: #fff; color: #000; border: 1px solid #000';		echo '">', $last, '</a>&nbsp;&nbsp;';				echo '<input type="text" id="page" name="page" size="2" style="text-align: center" onkeydown="return is_digit(event);">';		echo '&nbsp;<input type="submit" name="goto" value="', func::tr('Go'), '" onclick="return validate_goto();">';			}	echo '<br>';}?>