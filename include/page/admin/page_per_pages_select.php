<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

if($total>$per_pages[0]) {
	if($total<=$per_page) echo '<br>';
	echo '<br>', tr('Records per page'), ": <select name='per_page' onchange='document.$form_name.change_per_page.click()'>";
	foreach($per_pages as $value) {
		if($value!=$per_page) echo "<option>$value</option>";
		else echo "<option selected>$value</option>";
	}
	echo '</select>&nbsp;<input type="submit" value="', tr('Show'), '" name="change_per_page" style="display: visible">';
	echo  "<script>
	document.$form_name.change_per_page.style.display='none';
	</script>";
}

?>
