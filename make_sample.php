<?php

if (file_exists("sample.png")) {
    echo "Already Exists";
    exit; 
}

// Set the enviroment variable for GD
putenv('GDFONTPATH=' . realpath('.'));

$string1 = "RobertStrutts.com/me";
$string2 = "Save this image & Upload me, to that site";
$string3 = "to read a secret message!";

$im     = imagecreatefrompng("yoursign.png");

$red   = imagecolorallocate($im, 255, 0, 0);
$black = imagecolorallocate($im, 0, 0, 0);

$px1     = (imagesx($im) - 7.5 * strlen($string1)) / 2;
$y1 = 28;
$font = "arial.ttf";
$font_size = 20;
imagettftext($im, $font_size, 0, $px1, $y1, $red, $font, $string1);

$px2 = 235;
$y2 = 160;
imagestring($im, 3, $px2, $y2, $string2, $black);

$px3 = 235;
$y3 = 180;
imagestring($im, 3, $px3, $y3, $string3, $black);

if (is_resource($im)) {
    imagepng($im, 'sample.png');
    imagedestroy($im);
    echo "Updated Sample Image! Worked!";
} else {
    echo "Sorry, unable to update Sample Image!";
}