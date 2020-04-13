<?php

if (file_exists("sample.png")) {
    echo "Already Exists";
    exit; 
}

$string1 = "https://RobertStrutts.com/SocialMediaEncoder/image_decoder.php";
$string2 = "Upload (this image), to that site";
$string3 = "to read another message!";

$im     = imagecreatefrompng("yoursign.png");

$red   = imagecolorallocate($im, 255, 0, 0);
$black = imagecolorallocate($im, 0, 0, 0);

$px1     = (imagesx($im) - 7.5 * strlen($string1)) / 2;
$y1 = 3;
imagestring($im, 3, $px1, $y1, $string1, $red);

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