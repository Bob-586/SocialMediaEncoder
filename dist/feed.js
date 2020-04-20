function postAjax(url, data, success) {
    var params = typeof data == 'string' ? data : Object.keys(data).map(
            function(k){ return encodeURIComponent(k) + '=' + encodeURIComponent(data[k]) }
        ).join('&');

    var xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
    xhr.open('POST', url);
    xhr.onreadystatechange = function() {
        if (xhr.readyState>3 && xhr.status==200) { success(xhr.responseText); }
    };
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(params);
    return xhr;
}

function sleep(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}

function post() {
    var el = document.getElementById('enc').value.length;
    if (el < 40) {
        document.getElementById('msg').innerHTML = "Please write more...";
        throw "not written enough text";
    }
    document.getElementById('post').disabled = true;
    document.getElementById('msg').innerHTML = "Posting....";
    sleep(2500).then(() => {
        var enc = document.getElementById('enc').value;
        var pwd = document.getElementById('pwd').value;
        var l = (pwd.length >0);
        try {
            var ret = do_enc('xor', 'des', enc, pwd);
            var styles = get_styles();
            var tags = document.getElementById('tags').value;
            postAjax('do_post.php', { enc: ret, pass: l, style: styles, tags: tags }, function(json){
                var obj = JSON.parse(json);
                if (obj.hasOwnProperty('Failed')) {
                    document.getElementById('msg').innerHTML = obj.Failed;
                } else {
                    if (obj.hasOwnProperty('Success') && obj.hasOwnProperty('id')) {
                        var id = obj.id;
                        var url = window.location.href;
                        var url2 = url.replace("post.php", "");
                        var url_clean = url2.replace("index.php", "");
                        
                        var ds = "";
                        if (obj.hasOwnProperty('ds')) {
                            ds = "/" + obj.ds;
                        }
                        
                        var msg = obj.Success + ". Your Link is: " + url_clean + id + ds;
                        document.getElementById('msg').innerHTML = msg;
                        document.getElementById('post').disabled = false;
                        document.getElementById('enc').value = "";
                    }
                }
            });
        } catch (err) {
          if (typeof(err.message) != 'undefined') { 
             console.warn(err.message);
             alert(err.message);
             document.getElementById('post').disabled = false;
             document.getElementById('msg').innerHTML = "";
          } else {
             console.warn(err);
             alert(err);
             document.getElementById('post').disabled = false;
             document.getElementById('msg').innerHTML = "";
          }
        }
    });
}

function feed_fetch(id, vc) {
    postAjax('do_fetch.php', { id: id }, function(json) {
       document.getElementById('wait').innerHTML = "";
       var obj = JSON.parse(json);
       if (obj.hasOwnProperty('Failed')) {
           var list = document.getElementById('feed_update_list');
           var entry = document.createElement('li');
           entry.innerHTML = obj.Failed;
           list.appendChild(entry);
       } else if (obj.hasOwnProperty('approved') && obj.approved === "N") {
           var list = document.getElementById('feed_update_list');
           var entry = document.createElement('li');
           var reason = "";
           if (obj.hasOwnProperty('flags') && obj.flags.length > 3) {
               var flags = JSON.parse(obj.flags);
               for(var flag in flags) {
                   reason += " - (" + flags[flag] + ") Flagged as " + flag + " abuse.";
               }
           }
           entry.innerHTML = "Sorry, that message was BANNED! " + reason;
           list.appendChild(entry);
       } else if (obj === false) {
           var list = document.getElementById('feed_update_list');
           var entry = document.createElement('li');
           entry.innerHTML = "No Message Found!";
           list.appendChild(entry);
       } else {
           var pwd = "";
           if (obj.hasOwnProperty('has_pwd')) {
               var has_pwd = obj.has_pwd;
               if (has_pwd === 'Y') {
                   var password = document.getElementById('password');
                   var secret = document.getElementById('pwd');
                   if (typeof(password) != 'undefined' && password != null && password.style.display === "none") {
                       password.style.display = "block";
                       throw "Needs password"; 
                   } else if (typeof(secret) != 'undefined' && secret != null) {
                       pwd = secret.value;
                   }
               }
           }
           var confirmed = "";
           if (obj.hasOwnProperty('ds') && vc !== "") {
               var ds = obj.ds;
               confirmed = "Original Message was lost! This may not be the intended message the user wanted to share/post, anymore.";
               if (ds === vc) {
                   confirmed = "Message confirmed to match verification code. This is the intended message, user wanted to share/post.";
               }
           }
           var styles = "";
           if (obj.hasOwnProperty('style')) {
               styles = obj.style;
           }
           if (obj.hasOwnProperty('cypher')) {
                var data = obj.cypher;
                var dec = do_dec(data, pwd); 
                var breaks = dec.replace(/(?:\r\n|\r|\n)/g, '<br>');  
                var list = document.getElementById('feed_update_list');
                var entry = document.createElement('li');
                entry.style = styles;
                entry.innerHTML = breaks.trim();
                list.appendChild(entry);
                if (confirmed !== "") {
                    var div = document.createElement('div');
                    div.innerHTML = confirmed;
                    list.appendChild(div);
                }
                var ds = "";
                if (obj.hasOwnProperty('ds')) {
                    ds = "/" + obj.ds;
                }
                if (obj.hasOwnProperty('id')) {
                    var reporting_abuse = "<button onclick='btn_report(\""+obj.id+"\");'>Report Abuse</button>";                        
                    var msg = "<br>" + reporting_abuse + " ** The Shared Link for this message is &nbsp; &nbsp; " + mysite + obj.id + ds;
                    var span = document.createElement('span');
                    span.innerHTML = msg;
                    list.appendChild(span);
                }                
                var hr = document.createElement('hr');
                list.appendChild(hr);
           } else {
                document.getElementById('wait').innerHTML = "No Results Found";
           }
       }
    });
}


function feed_fetchs(page_no, limit) {
    postAjax('do_fetchs.php', { page: page_no, limit: limit}, function(json) {
       document.getElementById('wait').innerHTML = "";
       var obj = JSON.parse(json);
       if (obj.hasOwnProperty('Failed')) {
           var list = document.getElementById('feed_update_list');
           var entry = document.createElement('li');
           entry.innerHTML = obj.Failed;
           list.appendChild(entry);
       } else if (obj === false) {
           var list = document.getElementById('feed_update_list');
           var entry = document.createElement('li');
           entry.innerHTML = "No more Messages Found!";
           list.appendChild(entry);
       } else {
           for(var zz=0; zz < obj.length; zz++) {
               var styles = "";
               if (obj[zz].hasOwnProperty('style')) {
                   styles = obj[zz].style;
               }
               if (obj[zz].hasOwnProperty('cypher')) {
                    var data = obj[zz].cypher;
                    var dec = do_dec(data, ''); 
                    var breaks = dec.replace(/(?:\r\n|\r|\n)/g, '<br>');  
                    var list = document.getElementById('feed_update_list');
                    var entry = document.createElement('li');
                    var ds = "";
                    if (obj[zz].hasOwnProperty('ds')) {
                        ds = "/" + obj[zz].ds;
                    }
                    entry.style = styles;
                    entry.innerHTML = breaks.trim();
                    list.appendChild(entry);
                    if (obj[zz].hasOwnProperty('id')) {
                        var reporting_abuse = "<button onclick='btn_report(\""+obj[zz].id+"\");'>Report Abuse</button>";                        
                        var msg = "<br>" + reporting_abuse + " ** The Shared Link for this message is &nbsp; &nbsp; " + mysite + obj[zz].id + ds;
                        var span = document.createElement('span');
                        span.innerHTML = msg;
                        list.appendChild(span);
                    }
                    var hr = document.createElement('hr');
                    list.appendChild(hr);
               }
           }
       }
    });
}