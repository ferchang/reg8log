<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

define('CAN_INCLUDE', true);

?>

</script>
<script>
var tmp;

function highlight(row) {
	tmp=row.style.background;
	row.style.background="#fff";
}
function unhighlight(row) {
	row.style.background=tmp;
}

function green(id) {
	tmp=document.getElementById('row'+id).style.background="green";
}
function red(id) {
	tmp=document.getElementById('row'+id).style.background="red";
}
function orange(id) {
	tmp=document.getElementById('row'+id).style.background="orange";
}

function yellow(id) {
	tmp=document.getElementById('row'+id).style.background="yellow";
}
function normal(id) {
	if(id%2) tmp=document.getElementById('row'+id).style.background='<?php echo $color1 ?>';
	else tmp=document.getElementById('row'+id).style.background='<?php echo $color2 ?>';
}
function is_digit(e) {
	code = e.keyCode ? e.keyCode : e.which;
	if(code<48 || code>57) return false;
	else return true;
}

<?php if(!isset($per_page)) return; ?>

function validate_goto() {
<?php
echo '	last_page=', ceil($total/$per_page), ";\n";
?>
	page=document.getElementById('page').value;
	if(page<1 || page>last_page ) {
		alert(<?php
		echo "'", sprintf(func::tr('Page number must be between (including) 1 and %d.'), ceil($total/$per_page)), "'";
		?>);
		document.getElementById('page').value='';
		return false;
	}
	else return true;
}
