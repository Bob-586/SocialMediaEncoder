<?php require_once 'compression.php'; ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Social Media Encoder v1.3</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../dist/uikit.min.css">
        <link rel="stylesheet" href="../dist/feed.min.css">
        <link rel="stylesheet" href="../dist/vkb.css">
        <?php require_once 'fonts.php'; ?>
        <script type="text/javascript">
            var uri = window.location.href.toString();
            var grr = uri.split('/<?= basename(__DIR__) ?>/');
            var mysite = grr[0] + '/<?= basename(__DIR__) ?>/';    
        </script>        
        <script type="text/javascript" src="../dist/all.min.js?v=1.3" defer></script>
        <script type="text/javascript" src="../dist/feed.min.js?v=1.3" defer></script>
        <link rel="manifest" href="../manifest.json">
    </head>
    <body>
            
        <?php require_once 'post_body.php'; ?>
        
        <script type="text/javascript" src="../dist/vkb.min.js"></script>
        <script type="text/javascript" src="../dist/keyboard_layout.js"></script>

    </body>
</html>
