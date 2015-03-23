<?php
if(ini_get('register_globals')) exit("<center><h3>Error: Turn that damned register globals off!</h3></center>");
if(!defined('CAN_INCLUDE')) exit("<center><h3>Error: Direct access denied!</h3></center>");

/*
 This library is free software; you can redistribute it and/or modify it under the terms of the GNU Lesser General Public License as published by the Free Software Foundation; either version 2.1 of the License, or (at your option) any later version.

Project: The CAPTCHA. A PHP function for creating and verifying CAPTCHA images. See documentation.html in this package for help
File: captcha.function.php
Official site of the project: www.thecaptcha.com
Author: Eugene Orlov <eugene.orlov@gmail.com>
Copyright: 2007 Eugene Orlov
Version: 1.0 October 2007
*/

/*
Modified by hamidreza_mz -=At=- yahoo -=Dot=- com
Oct 2011
*/

/* If set to true, an additional small box containing string 'protected by thecaptcha.com' will be added to the bottom of the image. 
It's up to you, turn it to false if you don't like it. */
$captcha_show_credits = false;

#########################################################################
/* captcha_show_image() - outputs the image to browser and stores a CAPTCHA word in a cookie or a session file. */
function captcha_show_image() {
	
	global $session1;
	global $session0;
	
	// Let's create an image
	$GLOBALS['captcha_show_credits'] ? $captcha_image = imagecreate(200, 51) : $captcha_image = imagecreate(200, 40);
	
	// Random background and color scheme. Can be red, green or blue
	$captcha_backgrounds = array('FF0000', '0000FF');
	$captcha_color_scheme = $captcha_backgrounds[mt_rand(0, 1)];
	$captcha_colors = array(hexdec('0x'.$captcha_color_scheme{0}.$captcha_color_scheme{1}), hexdec('0x'. $captcha_color_scheme{2}.$captcha_color_scheme{3}), hexdec('0x'.$captcha_color_scheme{4}.$captcha_color_scheme{5}));
	$captcha_image_bgcolor = imagecolorallocate($captcha_image, $captcha_colors[0], $captcha_colors[1], $captcha_colors[2]);
	
	// Let's make some lighter and darker colors
	if ($captcha_color_scheme == 'FF0000') {
		$captcha_image_lcolor[] = imagecolorallocate($captcha_image, $captcha_colors[0], $captcha_colors[1]+mt_rand(230, 240), $captcha_colors[2]+mt_rand(230, 240));
		$captcha_image_lcolor[] = imagecolorallocate($captcha_image, $captcha_colors[0], $captcha_colors[1]+mt_rand(230, 240), $captcha_colors[2]+mt_rand(230, 240));
		$captcha_image_lcolor[] = imagecolorallocate($captcha_image, $captcha_colors[0], $captcha_colors[1]+mt_rand(160, 220), $captcha_colors[2]+mt_rand(160, 220));
		$captcha_image_dcolor[] = imagecolorallocate($captcha_image, $captcha_colors[0]-mt_rand(50, 100), $captcha_colors[1]+mt_rand(0, 50), $captcha_colors[2]+mt_rand(0, 50));
		$captcha_image_dcolor[] = imagecolorallocate($captcha_image, $captcha_colors[0]-mt_rand(50, 100), $captcha_colors[1]+mt_rand(0, 50), $captcha_colors[2]+mt_rand(0, 50));
		$captcha_image_dcolor[] = imagecolorallocate($captcha_image, $captcha_colors[0]-mt_rand(50, 100), $captcha_colors[1]+mt_rand(0, 50), $captcha_colors[2]+mt_rand(0, 50));
	} elseif ($captcha_color_scheme == '00FF00') {
		$captcha_image_lcolor[] = imagecolorallocate($captcha_image, $captcha_colors[0]+mt_rand(230, 240), $captcha_colors[1], $captcha_colors[2]+mt_rand(230, 240));
		$captcha_image_lcolor[] = imagecolorallocate($captcha_image, $captcha_colors[0]+mt_rand(230, 240), $captcha_colors[1], $captcha_colors[2]+mt_rand(230, 240));
		$captcha_image_lcolor[] = imagecolorallocate($captcha_image, $captcha_colors[0]+mt_rand(150, 190), $captcha_colors[1], $captcha_colors[2]+mt_rand(150, 190));
		$captcha_image_dcolor[] = imagecolorallocate($captcha_image, $captcha_colors[0]+mt_rand(0, 130), $captcha_colors[1]-mt_rand(50, 100), $captcha_colors[2]+mt_rand(0, 130));
		$captcha_image_dcolor[] = imagecolorallocate($captcha_image, $captcha_colors[0]+mt_rand(0, 130), $captcha_colors[1]-mt_rand(50, 100), $captcha_colors[2]+mt_rand(0, 130));
		$captcha_image_dcolor[] = imagecolorallocate($captcha_image, $captcha_colors[0]+mt_rand(0, 130), $captcha_colors[1]-mt_rand(50, 100), $captcha_colors[2]+mt_rand(0, 130));
	} else {
		$captcha_image_lcolor[] = imagecolorallocate($captcha_image, $captcha_colors[0]+mt_rand(210, 230), $captcha_colors[1]+mt_rand(210, 230), $captcha_colors[2]);
		$captcha_image_lcolor[] = imagecolorallocate($captcha_image, $captcha_colors[0]+mt_rand(210, 230), $captcha_colors[1]+mt_rand(210, 230), $captcha_colors[2]);
		$captcha_image_lcolor[] = imagecolorallocate($captcha_image, $captcha_colors[0]+mt_rand(180, 200), $captcha_colors[1]+mt_rand(180, 200), $captcha_colors[2]);
		$captcha_image_dcolor[] = imagecolorallocate($captcha_image, $captcha_colors[0]+mt_rand(0, 100), $captcha_colors[1]+mt_rand(0, 100), $captcha_colors[2]-mt_rand(70, 150));
		$captcha_image_dcolor[] = imagecolorallocate($captcha_image, $captcha_colors[0]+mt_rand(0, 100), $captcha_colors[1]+mt_rand(0, 100), $captcha_colors[2]-mt_rand(70, 150));
		$captcha_image_dcolor[] = imagecolorallocate($captcha_image, $captcha_colors[0]+mt_rand(0, 100), $captcha_colors[1]+mt_rand(0, 100), $captcha_colors[2]-mt_rand(70, 150));
	}
	
	// Background
	for ($i = 0; $i <= 10; $i++) {
		imagefilledrectangle($captcha_image, $i*20+mt_rand(4, 26), mt_rand(0, 39), $i*20-mt_rand(4, 26), mt_rand(0, 39), $captcha_image_dcolor[mt_rand(0, 2)]);
	}
	
	// Grid
	for ($i = 0; $i <= 10; $i++) {
		imageline($captcha_image, $i*20+mt_rand(4, 26), 0, $i*20-mt_rand(4, 26), 39, $captcha_image_lcolor[mt_rand(0, 2)]);
	}
	for ($i = 0; $i <= 10; $i++) {
		imageline($captcha_image, $i*20+mt_rand(4, 26), 39, $i*20-mt_rand(4, 26), 0, $captcha_image_lcolor[mt_rand(0, 2)]);
	}
	
	// This creates the captcha word
	$symbols = array('2', '3', '4', '5', '6', '7', '9', 'A', 'C', 'E', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 'N', 'P', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
	$captcha_word = '';
	$code_length=5;//if u change this u must modify the client side validation code in the login form too, because it checks for the length of the security code the user enters.
	for ($i = 0; $i < $code_length; $i++) {
		$captcha_word .= $symbols[mt_rand(0, 27)];
	}
	
	// Let's place the word. Each letter will have random position, size, angle and font
	if (function_exists('imagettftext')) {
		for($i = 0; $i < $code_length; $i++) {
			imagettftext($captcha_image, mt_rand(24, 28), mt_rand(-20, 20), $i*mt_rand(30, 36)+mt_rand(2,4), mt_rand(32, 36), $captcha_image_lcolor[mt_rand(0, 1)], './'.mt_rand(1, 3).'.ttf', $captcha_word{$i});
		}
	} else exit('<br />error: no ttf support for generating the captcha image!<br />');
	
	// Noise over the word
	imagesetstyle($captcha_image, array($captcha_image_dcolor[mt_rand(0, 2)], $captcha_image_dcolor[mt_rand(0, 2)], $captcha_image_dcolor[mt_rand(0, 2)], $captcha_image_dcolor[mt_rand(0, 2)], $captcha_image_dcolor[mt_rand(0, 2)], $captcha_image_dcolor[mt_rand(0, 2)], $captcha_image_dcolor[mt_rand(0, 2)]));
	for ($i = 0; $i <= 4; $i++) {
		imageline($captcha_image, 0, mt_rand(0, 39), 199, mt_rand(0, 39), IMG_COLOR_STYLED);
	}
	$captcha_image_lineys = array(mt_rand(0, 39), mt_rand(0, 39), mt_rand(0, 39), mt_rand(0, 39), mt_rand(0, 39), mt_rand(0, 39), mt_rand(0, 39), mt_rand(0, 39), mt_rand(0, 39), mt_rand(0, 39));
	$captcha_image_lineye = array(mt_rand(0, 39), mt_rand(0, 39), mt_rand(0, 39), mt_rand(0, 39), mt_rand(0, 39), mt_rand(0, 39), mt_rand(0, 39), mt_rand(0, 39), mt_rand(0, 39), mt_rand(0, 39));
	for ($i = 0; $i <= 4; $i++) {
		imageline($captcha_image, $i*20+mt_rand(1, 6), $captcha_image_lineys[$i], $i*16+mt_rand(1, 6), $captcha_image_lineye[$i], $captcha_image_lcolor[mt_rand(0, 1)]);
		imageline($captcha_image, $i*20+mt_rand(1, 6), $captcha_image_lineys[$i], $i*16+mt_rand(1, 6), $captcha_image_lineye[$i], $captcha_image_lcolor[mt_rand(0, 1)]);
	}
	
	// Credits
	if ($GLOBALS['captcha_show_credits']) {
		$captcha_creditsimg = imagecreatefrompng('protected.png');
		imagecopy($captcha_image, $captcha_creditsimg, 0, 40, 0, 0, 200, 11);
	}

//======================================

require ROOT.'include/code/sess/code_sess_start.php';

global $site_priv_salt;

require_once ROOT.'include/code/code_fetch_site_vars.php';

$_SESSION['reg8log']['captcha_hash'] = hash('sha256', $GLOBALS['pepper'].$site_priv_salt.$captcha_word);

//======================================
	
  	// Output the image to browser
	header('Content-type: image/png');
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Cache-Control: private, no-store, no-cache, must-revalidate, post-check=0, pre-check=0, max-age=0");
    header('Pragma: private');
    header("Pragma: no-cache");
	imagepng($captcha_image);
	imagedestroy($captcha_image);

}

/* captcha_verify_word() - verifies a word. Returns 'true' or 'false'. */
function captcha_verify_word() {
	
	global $session1;
	global $session0;

	require ROOT.'include/code/sess/code_sess_start.php';

	if(empty($_POST['captcha']) or empty($_SESSION['reg8log']['captcha_hash'])) return false;

	global $site_priv_salt;

	require_once ROOT.'include/code/code_fetch_site_vars.php';
	
	if(!(hash('sha256', $GLOBALS['pepper'].$site_priv_salt.strtoupper($_POST['captcha']))===$_SESSION['reg8log']['captcha_hash'])) {
		unset($_SESSION['reg8log']['captcha_hash']);
		return false;
	} else {
		unset($_SESSION['reg8log']['captcha_hash']);
		$_SESSION['reg8log']['captcha_verified']=true;
		return true;
	}
	
}
?>
