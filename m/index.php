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
        <?php
require_once 'db.php';
require_once 'paginate.php';
               
$limit = $_GET['limit'] ?? 2;
$page = $_GET['page'] ?? 1;
if (! filter_var($limit, FILTER_VALIDATE_INT)) {
    $limit = 2;
}
if (! filter_var($page, FILTER_VALIDATE_INT)) {
    $page = 1;
}
if ($limit > 11) {
    $limit = 10; // Don't go nutts with Cypher-Text!
}
$pdo = get_db();
$sql = "SELECT `id` FROM `posts` WHERE `approved`='Y' && `has_pwd`='N' ORDER BY `ts` DESC";
$pag = new paginate($pdo, $sql);
$pag->set_pages($limit, $page);
$links = $pag->create_links();
?>
        <script type="text/javascript">feed_fetchs('<?= $page ?>', '<?= $limit ?>');</script>
    </head>
    <body>
            
        <section>
        <fieldset>
        <legend>Message to Post</legend>    
            <a id="copybtn" class="clipboard" title="Copy/Paste"></a>
            <div style="float: right;" class="nomobile">
                <img src="../dist/lgplv3-with-text-154x68.png" alt="License: LGPLv3 Free as in Freedom" />
                <br>
                <a href="https://github.com/Bob-586/SocialMediaEncoder" id="dload" class="rlink" target="_blank"><img src="../images/dload.svg" class="dload" alt="Download your copy of this project here." title="Download your copy of this project here.">Add this code/project to your own site! * PHP7-required</a>
            </div>
            <textarea id="enc" name="enc" rows="9" cols="76" class="input" maxlength="30000" placeholder="Bypassing Censorship , enter your important message , here :"></textarea>
        <div id="main" style="visibility: hidden;">
            <label for="pwd">*(optional) Password for Group:</label>
            <input type="password" id="pwd" />
            <button onclick="show_vkb(); return false;" id="btn-svkb">Show Virtual Keyboard, to avoid key stroke logging</button>
            <div class="simple-keyboard" id="dvkb" style="display: none;"></div>
            <?php require_once 'style.php'; ?>
            <br>
            <button onclick="post();" id="post" class="uk-button uk-button-main uk-button-small">Post</button>
            <span id="msg"></span>
        </div>
        </fieldset>
        </section>
        <div id="wait">Decoding Messages...Please wait a few seconds...!</div>
        <div id="feed_container">
               <ul id="feed_update_list"></ul>
        </div>
        
        <?php require_once 'report.php'; ?>
        
        <?= $links ?>
        
        <script type="text/javascript" src="../dist/vkb.min.js"></script>
        <script type="text/javascript" src="../dist/keyboard_layout.js"></script>
    </body>
</html>
