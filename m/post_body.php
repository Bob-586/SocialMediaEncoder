<?php
$cookie_name = "sme_posts";
$track = (isset($_COOKIE[$cookie_name])) ? "checked" : "";
?>
<section>
<fieldset>
<legend>Message to Post</legend>    
            <a id="copybtn" class='clipboard' title='Copy/Paste'></a>
            <div style="float: right;" class="nomobile" id="gpl">
                <img src="../dist/lgplv3-with-text-154x68.png" alt="License: LGPLv3 Free as in Freedom" />
                <br>
                <a href="https://github.com/Bob-586/SocialMediaEncoder" id="dload" class="rlink" target="_blank"><img src="../images/dload.svg" class="dload" alt="Download your copy of this project here." title="Download your copy of this project here.">Add this code/project to your own site! * PHP7-required</a>
            </div>
            <textarea id="enc" name="enc" rows="4" cols="70" style="width: 490px; height: 118px; margin: 0px;" class="input" maxlength="30000" placeholder="Bypassing Censorship , enter your important message , here :"></textarea>
        <div id="main" style="visibility: hidden;">
            <section>
            <fieldset>
            <legend>Message Options</legend>    
                <input type="text" id="tags" maxlength="200" /><label for="tags">*(optional) Hash Tags</label>
                <br>
                <input type="password" id="pwd" /><label for="pwd">*(optional) Password for Group</label>
                <br>
                <input type="checkbox" id="track" value="true" <?= $track ?>/><label for="track">Remember my posts</label>
<?php if ($track === "checked") { ?>                
                &nbsp; &nbsp; <a href="relookat.php">Re-Look-At-Your-Past-Posts</a>
<?php } ?>                
                <br>
                <button onclick="show_vkb(); return false;" id="btn-svkb">Show Virtual Keyboard, to avoid key stroke logging</button>
                <button onclick="this.style.display='none'; document.getElementById('gpl').style.display='none'; document.getElementById('enc').style.width = '95%';">Full Screen - My Message Box</button>
            </fieldset>
            </section>
            <div class="simple-keyboard" id="dvkb" style="display: none;"></div>
            <?php require_once 'style.php'; ?>
            <br>
            <button onclick="post();" id="post" class="uk-button uk-button-main uk-button-small">Post</button>
            <span id="msg"></span>
        </div>
</fieldset>
</section>