<?php require_once 'compression.php'; ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Social Media Encoder v1.3</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../dist/uikit.min.css">
        <link rel="stylesheet" href="../dist/feed.min.css">
        <link rel="stylesheet" href="../dist/vkb.min.css">
        <link rel="apple-touch-icon" sizes="180x180" href="../images/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="../images/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon/favicon-16x16.png">
        <link rel="icon" type="image/gif" sizes="16x16" href="../images/favicon/binary.gif">        
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
        <div id="posting"></div>
        <div id="showpostbtn" style="display: none;"><br><a href="#Post" onclick="document.getElementById('posting').style.display='block'; document.getElementById('showpostbtn').style.display='none';" class="uk-button uk-button-secondary uk-button-large">POST new Message</a></div>
        <div id="showfeedbtn" style="display: none;"><br><a href="#Page/1/3" onclick="document.getElementById('showfeedbtn').style.display='none';" class="uk-button uk-button-secondary uk-button-small">Show Messages Feed</a></div>
        
        <div id="pag-links"></div>
        
        <div id="wait"></div>
        <div id="feed_container">
               <ul id="feed_update_list"></ul>
        </div>

        <?php require_once 'report.php'; ?>
        
        <footer><div id="footer-pag-links" style="display: none;"></div></footer>
        
        <script type="text/javascript" src="../dist/post_styles.min.js"></script>
        <script type="text/javascript" src="../dist/route.min.js"></script>
    </body>
</html>
