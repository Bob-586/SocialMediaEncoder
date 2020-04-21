<?php

function sanitize_output($buffer) {

    $search = array(
        '/\>[^\S ]+/s',     // strip whitespaces after tags, except space
        '/[^\S ]+\</s',     // strip whitespaces before tags, except space
        '/(\s)+/s',         // shorten multiple whitespace sequences
        '/<!--(.|\s)*?-->/', // Remove HTML comments
        '/\/(\*)[^*]*\*+(?:[^*\/][^*]*\*+)*\//',    // Remove JS block comments
        '#^\s*//.+$#m' // Remove JS single line comment
    );

    $replace = array(
        '>',
        '<',
        '\\1',
        '',
        '',
        ''
    );

    $buffer = preg_replace($search, $replace, $buffer);
    
    $use_page_cache = true;
    if ($use_page_cache) {
        $hash = crc32($buffer);
        $headers = getallheaders();
        if (isset($headers['If-None-Match']) && ereg($hash, $headers['If-None-Match'])) {
            header('HTTP/1.1 304 Not Modified');
            exit;
        }
        header("ETag: \"$hash\"");
    }
    return ob_gzhandler($buffer, 5);
}

ob_start("sanitize_output");