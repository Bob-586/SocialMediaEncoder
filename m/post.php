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
        <script type="text/javascript" src="../dist/all.min.js?v=1.3" defer></script>
        <script type="text/javascript" src="../dist/feed.min.js" defer></script>
        <link rel="manifest" href="../manifest.json">
    </head>
    <body>
            
        <section>
        <fieldset>
        <legend>Message to Post</legend>    
            <a id="copybtn" class='clipboard' title='Copy/Paste'></a>
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
            <?php require_once 'style.php'; ?>
            <br>
            <button onclick="post();" id="post" class="uk-button uk-button-main uk-button-small">Post</button>
            <span id="msg"></span>
        </div>
        </fieldset>
        </section>
        
        <script type="text/javascript" src="../dist/vkb.min.js"></script>
        <script type="text/javascript" src="../dist/keyboard_layout.js"></script>

    </body>
</html>
