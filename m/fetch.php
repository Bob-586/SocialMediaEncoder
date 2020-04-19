<?php 
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
        <script type="text/javascript" src="../dist/all.min.js?v=1.3"></script>
        <script type="text/javascript" src="../dist/feed.min.js"></script>
        <script type="text/javascript">
                feed_fetch('<?= $id ?>', '<?= $vc ?>');
        </script>
    </head>
    <body>
        <div id="wait">Decoding your Message...Please wait a few seconds...!</div>
        <div id="password" style="display: none;">
            <label for="pwd">Required Password: </label>
            <input type="password" id="pwd" />
            <button onclick="feed_fetch('<?= $_GET['id'] ?>', '<?= $vc ?>');">Decode</button>
        </div>
        <div id="feed_container">
               <button onclick="btn_report('<?= $_GET['id'] ?>');" style="display: none;" id="btn-abuse">Report Abuse</button>
               <ul id="feed_update_list"></ul>
        </div>

        <a href="post.php" class="uk-button uk-button-primary uk-button-large">Encode new message</a>
        
        <?php require_once 'report.php'; ?>
        
    </body>
</html>            