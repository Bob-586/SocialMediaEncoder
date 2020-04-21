<?php require_once 'compression.php'; ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Social Media Encoder v1.3</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../dist/uikit.min.css">
        <link rel="stylesheet" href="../dist/feed.min.css">
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
        <div style="float: right;"><a href="post.php">Just POST a new Message</a></div>
        <div id="pag-links"></div>
        
        <div id="wait">Decoding Messages...Please wait a few seconds...!</div>
        <div id="feed_container">
               <ul id="feed_update_list"></ul>
        </div>
        
        <?php require_once 'report.php'; ?>
        
        <script type="text/javascript">
var router = new Grapnel();            

router.on('navigate', function() {
       document.getElementById('wait').innerHTML = "Decoding Messages...Please wait a few seconds...!";
});

router.get('Page/:page/:limit', function (req) {
        postAjax('links.php', { show: true, page: req.params.page, limit: req.params.limit }, function(data) {
            document.getElementById('pag-links').innerHTML = data;
        });
        document.getElementById('feed_update_list').innerHTML = "";
        feed_fetchs(req.params.page, req.params.limit);
});

/* Keep this as the very last Route, for 404 to work right! */
router.get('*', function (req, e) {
  if (!e.parent()) {
      document.getElementById('wait').innerHTML = "404 - Page NOT Found!";
  }
});

/* Redirect user if no hash tag was found */
if (location.hash == '') {
        router.navigate('#Page/1/<?= $_GET['limit'] ?? 3 ?>');
}
        </script>
        
    </body>
</html>
