<?php

$cookie_name = "sme_posts";
if(!isset($_COOKIE[$cookie_name])) {
    $cookie_value = "";
} else {
    $cookie_value = $_COOKIE[$cookie_name] ?? "";
}

if (! empty($cookie_value)) {
    $a_ids = json_decode(base64_decode($cookie_value), true);
    $i =0;
    foreach($a_ids as $id) {
        $i++;
        echo "<a href=\"fetch.php?id={$id}\">MSG #{$i}</a> &nbsp; &nbsp; " . PHP_EOL;
    }
}