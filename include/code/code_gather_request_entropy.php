<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

if(isset($store_request_entropy_probability2)) config::set('store_request_entropy_probability', $store_request_entropy_probability2);

if(!isset($GLOBALS['request_entropy'])) $GLOBALS['request_entropy']=sha1(microtime().$GLOBALS['pepper'].$_SERVER['REMOTE_ADDR'].$_SERVER['REMOTE_PORT'].$_SERVER['HTTP_USER_AGENT'].serialize($_POST).serialize($_GET).serialize($_COOKIE));
//similar code in func_random.php

/* using the entropy of GPC data was the idea of Jim Wigginton
(the author of the phpseclib library)
using them with the serialize function was also his idea.
using sha1 function for combining entropy of all requests was also his idea
see: http://www.frostjedi.com/phpbb/viewtopic.php?f=46&t=30875
*/

if(!config::get('store_request_entropy_probability') or !DB_INSTALLED) return;

if(config::get('store_request_entropy_probability')!==1 and mt_rand(1, floor(1/config::get('store_request_entropy_probability')))!==1) return;

$query="update `site_vars` set `value`=sha1(concat(`value`, '$request_entropy')) where name='entropy'";

$GLOBALS['reg8log_db']->query($query);

?>