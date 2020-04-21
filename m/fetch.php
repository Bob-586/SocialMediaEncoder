<?php 

require_once 'compression.php'; 

$vc = $_GET['vc'] ?? '';
$id = $_GET['id'] ?? '';
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Social Media Encoder v1.3</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../dist/uikit.min.css">
        <link rel="stylesheet" href="../dist/feed.min.css">
        <?php require_once 'fonts.php'; ?>
        <link rel="apple-touch-icon" sizes="180x180" href="../images/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="../images/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon/favicon-16x16.png">
        <link rel="icon" type="image/gif" sizes="16x16" href="../images/favicon/binary.gif">        
        <link rel="manifest" href="../manifest.json">        
        <script type="text/javascript" src="../dist/all.min.js?v=1.3"></script>
        <script type="text/javascript" src="../dist/feed.min.js"></script>
        <script type="text/javascript">
                var uri = window.location.href.toString();
                var grr = uri.split('/<?= basename(__DIR__) ?>/');
                var mysite = grr[0] + '/<?= basename(__DIR__) ?>/';    
                feed_fetch('<?= $id ?>', '<?= $vc ?>');
        </script>
    </head>
    <body>
        <div id="wait">Decoding your Message...<b>((Please wait a few seconds))...</b>!</div>
        <div id="password" style="display: none;">
            <label for="pwd">Required Password: </label>
            <input type="password" id="pwd" />
            <button onclick="feed_fetch('<?= $id ?>', '<?= $vc ?>');">Decode</button>
        </div>
        <div id="feed_container">
               <ul id="feed_update_list"></ul>
        </div>

        <a href="index.php#Post" class="uk-button uk-button-secondary uk-button-large">Encode & POST - new message</a>
        <a href="index.php#Page/1/3" class="uk-button uk-button-primary uk-button-large">View Message Feed</a>
        <?php require_once 'report.php'; ?>
        
    </body>
</html>            