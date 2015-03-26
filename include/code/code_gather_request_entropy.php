<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(isset($store_request_entropy_probability2)) config::set('store_request_entropy_probability', $store_request_entropy_probability2);

@$request_entropy=sha1(microtime().config::get('pepper').$_SERVER['REMOTE_ADDR'].$_SERVER['REMOTE_PORT'].$_SERVER['HTTP_USER_AGENT'].serialize($_POST).serialize($_GET).serialize($_COOKIE));

/* using the entropy of GPC data was the idea of Jim Wigginton
(the author of the phpseclib library)
using them with the serialize function was also his idea.
using sha1 function for combining entropy of all requests was also his idea
see: http://www.frostjedi.com/phpbb/viewtopic.php?f=46&t=30875
*/

if(!config::get('store_request_entropy_probability') or !config::get('db_installed')) return;

if(config::get('store_request_entropy_probability')!==1 and mt_rand(1, floor(1/config::get('store_request_entropy_probability')))!==1) return;

require_once ROOT.'include/code/code_db_object.php';

$query="update `site_vars` set `value`=sha1(concat(`value`, '$request_entropy')) where name='entropy'";

$reg8log_db->query($query);

?>