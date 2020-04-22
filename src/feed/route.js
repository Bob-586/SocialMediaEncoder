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

function get_pwd_from_hash() {
    var vc = "";
    var lh = location.hash;
    var index = lh.indexOf('/');
    /* Message Hash Found */
    if (lh.indexOf('#Message') === 0) {
        var hash_tags = lh.split('#Message');
        var all_tags = hash_tags[1];
        var split_tags = all_tags.split('/');
        var msg_id = split_tags[1];
        var vc = split_tags[2];
        if (typeof(vc) === "undefined") {
             vc = "";
        }
        return { msg: msg_id, vc: vc };
    } 
    /* No Slash here */
    if (index === -1) {
        var hash_tags = lh.split('#');
        msg_id = hash_tags[1];
        return { msg: msg_id, vc: vc };
    } 
    var hash_index = lh.indexOf('#');
    /* No Hash */
    if (hash_index === -1) {
        var split_tags = lh.split('/');
        var msg_id = split_tags[1];
        var vc = split_tags[2];
        if (typeof(vc) === "undefined") {
            vc = "";
        }
        return { msg: msg_id, vc: vc };
    }
    /* Hash Route */
    var hash_tags = lh.split('#');
    var all_tags = hash_tags[1];
    var split_tags = all_tags.split('/');
    var msg_id = split_tags[0];
    var vc = split_tags[1];
    if (typeof(vc) === "undefined") {
         vc = "";
    }
    return { msg: msg_id, vc: vc };
}

function passworded() {
    var ret = get_pwd_from_hash();
    feed_fetch(ret.msg, ret.vc);
}

var router = new Grapnel();            

router.on('navigate', function() {
       document.getElementById('wait').innerHTML = "";
       document.getElementById('password').style.display = "none";
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

function do_msg_route(req) {
        document.getElementById('pag-links').innerHTML = "";
        document.getElementById('footer-pag-links').style.display = "none";
        document.getElementById('wait').innerHTML = "Decoding your Message...<b>((Please wait a few seconds))...</b>!";
        document.getElementById('showfeedbtn').style.display = "block";
        toggle_post_btns();
        document.getElementById('feed_update_list').innerHTML = "";
        var vc = req.params.vc;
        if (typeof(vc) === "undefined") {
            vc = "";
        }
        feed_fetch(req.params.msg, vc);
}

function was_it_found(arr, item) {
  for (var i = 0; i < arr.length; i++) {
    if (arr[i] == item) { return true; }
  }
  return false;
}

router.get(':msg/:vc?', function (req) {
    var skip_existing_routes = [
        "Post", "Message"
    ];
   
    if (was_it_found(skip_existing_routes, req.params.msg) === false) {
        do_msg_route(req);
    }
});

router.get('Message/:msg/:vc?', function (req) {
        do_msg_route(req);
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