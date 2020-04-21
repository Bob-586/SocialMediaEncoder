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
        <script type="text/javascript" src="../dist/all.min.js?v=1.3"></script>
        <script type="text/javascript" src="../dist/feed.min.js?v=1.3"></script>
        <link rel="manifest" href="../manifest.json">
        <?php require_once 'links.php'; ?>
        <script type="text/javascript">feed_fetchs('<?= $page ?>', '<?= $limit ?>');</script>
    </head>
    <body>
            
        <?php require_once 'post_body.php'; ?>
        <div style="float: right;"><a href="feed.php">Just the Messages Feed</a></div>
        <?= $links ?>
        <div id="wait">Decoding Messages...Please wait a few seconds...!</div>
        <div id="feed_container">
               <ul id="feed_update_list"></ul>
        </div>
        
        <?php require_once 'report.php'; ?>
        
        <script type="text/javascript" src="../dist/vkb.min.js"></script>
        <script type="text/javascript" src="../dist/keyboard_layout.js"></script>
    </body>
</html>
