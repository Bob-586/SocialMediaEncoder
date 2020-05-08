<?php
$cookie_name = "sme_posts";
$track = (isset($_COOKIE[$cookie_name])) ? "checked" : "";
?>
<section>
<fieldset>
<legend>Message to Post</legend>    
            <a id="copybtn" class='clipboard' title='Copy/Paste'></a>
            <!-- include 'gpl file' -->
            <textarea id="enc" name="enc" rows="4" cols="70" style="width: 490px; height: 118px; margin: 0px;" class="input" maxlength="30000" placeholder="Awaken, save us all... Do not whisper; don’t coward; be brave, what’s truly on your mind? What wrongs are being done? Voice / Shout out the issues of the day / big ticket items. Be Remembered for your courage against evil doing. What’s your life purpose / Calling to help others? Will your name be in the book of life for your actions of bravery in the face of evil? Post your message HERE: ->"></textarea>
        <div id="main" style="visibility: hidden;">
            <section>
            <fieldset>
            <legend>Message Options</legend>    
                <input type="text" id="tags" name="tags" maxlength="200" autocomplete="off" /><label for="tags">*(optional) Hash Tags</label>
                <br>
                <input type="password" id="pwd" autocomplete="off" /><label for="pwd">*(optional) Password for Group</label>
                <br>
                <input type="checkbox" id="track" value="true" <?= $track ?>/><label for="track">Remember my posts</label>
<?php if ($track === "checked") { ?>                
                &nbsp; &nbsp; &nbsp; &nbsp; <a href="relookat.php">Re-Look-At-Your-Past-Posts</a>
<?php } ?>                
                <br>
                <button onclick="show_vkb(); return false;" id="btn-svkb">Show Virtual Keyboard, to avoid key stroke logging</button>
                <button onclick="this.style.display='none'; document.getElementById('gpl').style.display='none'; document.getElementById('enc').style.width = '95%';">Full Screen - My Message Box</button>
            </fieldset>
            </section>
            <div class="simple-keyboard" id="dvkb" style="display: none;"></div>
            <div id="styles"></div>
            <br>
            <button onclick="post();" id="post" class="uk-button uk-button-main uk-button-small">Submit Post</button>
            <div style="float: right;"><button onclick="document.getElementById('posting').style.display='none';document.getElementById('showpostbtn').style.display = 'block';">Close Post - Section</button></div>
            <span id="msg"></span>
        </div>
</fieldset>
</section>