function show_main() {
    document.getElementById('main').style.visibility = "visible"; 
    if (screen.width < 1080) {
        var x = document.getElementsByClassName('nomobile');
        var i;
        for (i = 0; i < x.length; i++) {
            x[i].style.display = "none";
        }
    }            
}

function toggle_post_btns() {
    var is_post = document.getElementById('posting');
    if (is_post.innerHTML === "" || is_post.style.display === "none") {
        document.getElementById('showpostbtn').style.display = "block";
    } else {
        document.getElementById('showpostbtn').style.display = "none";
    }
}

function toggle_feed_btns() {
    var is_feed = document.getElementById('feed_update_list').innerHTML;
    if (is_feed === "") { 
        document.getElementById('showfeedbtn').style.display = "block";
    } else {
        document.getElementById('showfeedbtn').style.display = "none";
    }
}

function in_post() {
    document.getElementById('showpostbtn').style.display = "none";
}

function in_feed() {
    document.getElementById('showfeedbtn').style.display = "none";
}

function find_script(id) {
    var ii, xs = document.getElementsByTagName('script');
    for (ii = 0; ii < xs.length; ii++) {
        if ( xs[ii].id === id ) {
           return true;
        }
    }
    xs = document.getElementsByTagName('head');
    for (ii = 0; ii < xs.length; ii++) {
        if ( xs[ii].id === id ) {
            return true;
        }
    }
    return false;
}

function load_keyboard() {
        var elm = (document.getElementsByTagName('script')[0] || document.getElementsByTagName('head')[0]);
        if (find_script('vkb') === false) {
            var script = document.createElement('script');
            script.type = "text/javascript";
            script.id = "vkb";
            script.src = "../dist/vkb.min.js";
            elm.parentNode.insertBefore(script, elm);
        }
        if (find_script('layout') === false) {
            sleep(1500).then(function() {
                var script2 = document.createElement('script');
                script2.type = "text/javascript";
                script2.id = "layout";
                script2.src = "../dist/keyboard_layout.js";
                elm.parentNode.insertBefore(script2, elm);
            });
        }
}            

var router = new Grapnel();            

router.on('navigate', function() {
       document.getElementById('wait').innerHTML = "";
});

router.get('Page/:page/:limit', function (req) {
        in_feed();
        document.getElementById('footer-pag-links').style.display = "none";
        document.getElementById('wait').innerHTML = "Decoding your Message...<b>((Please wait a few seconds))...</b>!";
        postAjax('links.php', { show: true, page: req.params.page, limit: req.params.limit }, function(data) {
            document.getElementById('pag-links').innerHTML = data;
            document.getElementById('footer-pag-links').innerHTML = data;
        });
        toggle_post_btns();
        document.getElementById('feed_update_list').innerHTML = "";
        feed_fetchs(req.params.page, req.params.limit);
});

router.get('Post', function (req) {
        in_post();
        if (document.getElementById('posting').innerHTML === "") {
            postAjax('post_body.php', { }, function(data) {
                document.getElementById('posting').innerHTML = data;
                fetch_styles();
                show_main();
                load_keyboard();
            });
        } else {
            if (document.getElementById('dvkb').innerHTML === "") {
                load_keyboard();
            }
        }
        toggle_feed_btns();
});

/* Keep this as the very last Route, for 404 to work right! */
router.get('*', function (req, e) {
  if (!e.parent()) {
      document.getElementById('wait').innerHTML = "404 - Page NOT Found!";
  }
});

/* Redirect user if no hash tag was found */
if (location.hash == '') {
        router.navigate('#Page/1/3');
}