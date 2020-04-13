<?php

require __DIR__ . '/vendor/autoload.php';

try {
    $enc = $_POST['enc'] ?? false;
    $len = strlen($enc);
    
    if ($len > 60000) {
        $image = "big_sample.png";
    } else {
        $image = "sample.png";
    }
    
    $processor = new KzykHys\Steganography\Processor();
    $image = $processor->encode($image, $enc); // jpg|png|gif
    
    header('Content-Disposition: attachment; filename=fortune_cookie.png');
    header('Content-Type: application/octet-stream');

    // Or outout image to stdout
    $image->render();
} catch (Exception $ex) {
    echo $ex->getMessage();
}

// Save image to file
//$image->write('shhhimage.png'); // png only