<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!isset($parent_page)) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(!isset($index_dir)) $index_dir='';

foreach (glob('sql/*.sql') as $file) {
  $contents=file_get_contents($file);
  $query=substr($contents, strpos($contents, 'CREATE TABLE IF NOT EXISTS'));
  $reg8log_db->query($query);
  $tablename=basename($file, '.sql');
  echo 'Table <span style="color: green">'.$tablename.'</span> created.<br>';
}

?>
