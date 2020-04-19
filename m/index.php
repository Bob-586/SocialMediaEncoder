<!DOCTYPE html>
<html>
    <head>
        <title>Social Media Encoder v1.3</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../dist/uikit.min.css">
        <link rel="stylesheet" href="../dist/feed.min.css">
        <link rel="stylesheet" href="../dist/vkb.css">
        <script type="text/javascript" src="../dist/all.min.js?v=1.3"></script>
        <script type="text/javascript" src="../dist/feed.min.js?v=1.3"></script>
        <script type="text/javascript">
            var page = 1;
            function load_more() {
                feed_fetchs(page);
                page++;
            }
            load_more();
        </script>
    </head>
    <body>
            
        <section>
        <fieldset>
        <legend>Message to Post</legend>    
            <a id="copybtn" class='clipboard' title='Copy'></a>
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
            <div class="simple-keyboard" id="dvkb" style="display: none;"></div><br><br>
            <button onclick="post();" id="post" class="uk-button uk-button-main uk-button-small">Post</button>
            <span id="msg"></span>
        </div>
        </fieldset>
        </section>
        <div id="wait">Decoding Messages...Please wait a few seconds...!</div>
        <div id="feed_container">
               <ul id="feed_update_list"></ul>
        </div>
        <div id="pages" style="display: none;"><button onclick="load_more()">Load More</button></div>
        <?php require_once 'report.php'; ?>
        
        <script type="text/javascript" src="../dist/vkb.min.js"></script>
        <script type="text/javascript" src="../dist/keyboard_layout.js"></script>
    </body>
</html>
