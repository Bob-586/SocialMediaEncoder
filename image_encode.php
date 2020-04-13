<?php

header('Content-Disposition: attachment; filename=fortune_cookie.png');
header('Content-Type: application/octet-stream');

require __DIR__ . '/vendor/autoload.php';
$processor = new KzykHys\Steganography\Processor();
$image = $processor->encode('sample.png', $_POST['enc']); // jpg|png|gif

// Or outout image to stdout
$image->render();

// Save image to file
//$image->write('shhhimage.png'); // png only