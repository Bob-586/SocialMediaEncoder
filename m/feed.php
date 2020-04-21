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
        <link rel="manifest" href="../manifest.json">
        <?php require_once 'fonts.php'; ?>
        <script type="text/javascript">
            var uri = window.location.href.toString();
            var grr = uri.split('/<?= basename(__DIR__) ?>/');
            var mysite = grr[0] + '/<?= basename(__DIR__) ?>/';    
        </script>
        <script type="text/javascript" src="../dist/grapnel.min.js"></script>
        <script type="text/javascript" src="../dist/all.min.js?v=1.3"></script>
        <script type="text/javascript" src="../dist/feed.min.js?v=1.3"></script>
    </head>
    <body>
        <div id="posting"><a href="#Post">Just POST a new Message</a></div>
        <div id="unhidepost"></div>
        
        <div id="pag-links"></div>
        
        <div id="wait"></div>
        <div id="feed_container">
               <ul id="feed_update_list"></ul>
        </div>
        
        <?php require_once 'report.php'; ?>
        <script type="text/javascript" src="../dist/post_styles.min.js"></script>
        <script type="text/javascript" src="../dist/route.min.js"></script>
    </body>
</html>
