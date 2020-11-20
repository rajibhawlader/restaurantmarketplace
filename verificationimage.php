<?php
// ----------------------------------------- 
//  The Web Help .com
// ----------------------------------------- 

header('Content-type: image/jpeg');

$width = 65;
$height = 24;

$my_image = imagecreatetruecolor($width, $height);
//$image = ImageCreateFromJPEG("background$bgNum.jpg");
imagefill($my_image, 0, 0, 0xFFFFFF);

// add noise
for ($c = 0; $c < 100; $c++){
	$x = rand(0,$width-1);
	$y = rand(0,$height-1);
	imagesetpixel($my_image, $x, $y, 0x000000);
	}

$x = rand(1,10);
$y = rand(1,10);

$rand_string = $_REQUEST["encid"];
imagestring($my_image, 10, $x, $y, $rand_string, 003300);

setcookie('tntcon',(md5($rand_string).'a4xn'));

imagejpeg($my_image);
imagedestroy($my_image);
?>