<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");
////
$secure_hash_rounds=16; //note that actual number of rounds is 2^$secure_hash_rounds

$pepper="89JPa36HW7Uiq348dX10ks"; //composed of at least 22 chars of upper and lowercase letters + digits

$encrypt_session_files_contents=false; //whether to encrypt session file contents on the server
//encryption with AES 128 CBC + HMAC with key md5($pepper+$site_encr_key+client_key) will be used

$store_request_entropy_probability=0.1; // possible values: a number equal to or less than 1.
// 1: always store
/* a number < 1 means store with that probability; e.g. 0.1 causes to store entropy randomly with a chance of 0.1 in each request. this is meant to fine tune database load if needed.
note that this variable can be overriden in each page (before including common.php) by setting a variable named $store_request_entropy_probability2.
e.g. I have set $store_request_entropy_probability2 to 1 on pages with very precious entropy, like the register page.
*/

?>