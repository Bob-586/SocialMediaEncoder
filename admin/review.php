<?php
require_once 'start_html.php';
?>
<style>
#feed_container {
   min-height:45px;
   height:auto !important;
   height:40px;
   padding-bottom:10px;
}

#feed_update_list {
   padding: 0;
   overflow: hidden;
   font-family: 'Times New Roman', Times, Georgia, Serif;
   font-size: 16px;
   font-style: italic;
   color: #31353d;
   line-height: 16px;
   font-weight:bold;
}

#feed_update_list li {
   list-style: none;
}
.feedhr { 
  border : 0;
  border-top: 3px double #DC143C;
}

.msghr { 
  border : 0;
  border-top: 8px double greenyellow;
}
</style>
<script type="text/javascript" src="../dist/all.min.js?v=1.3"></script>
<?php
require_once '../m/db.php';

make_session_started();
$is_a_admin = has_role('admin');
if (! $is_a_admin) {
    echo "Access Denied!";
    exit;
}

require_once '../m/paginate.php';

$pdo = get_db();

$limit = $_GET['limit'] ?? 3;
$page = $_GET['page'] ?? 1;
$see_banned = $_GET['see_banned'] ?? false;
$approved = ($see_banned === false || $see_banned === "N") ? "Y" : "N";
if ($see_banned === "all") {
    $do = "all";
    $status = "";
} else {
    $do = $approved;
    $status ="`approved`='{$approved}' && ";
}

if (! filter_var($limit, FILTER_VALIDATE_INT)) {
    $limit = 10;
}
if ($limit > 11) {
    $limit = 10; // Don't go nutts with Cypher-Text!
}
if (! filter_var($page, FILTER_VALIDATE_INT)) {
    $page = 1;
}
$sql = "SELECT `id`, `cypher`, `flags`, `approved` FROM `posts` WHERE {$status}`has_pwd`='N' ORDER BY `ts` DESC";
$pag = new paginate($pdo, $sql);
$pag->set_links(['see_banned'=>$do]);
$results = $pag->get_data($limit, $page); 
$links = $pag->create_jump_menu_with_links();
?>
<div id="feed_container">
    <ul id="feed_update_list"></ul>
</div>
<script type="text/javascript">
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
function grab_text(data, id, flags, approved) { 
    var dec = do_dec(data, ''); 
    var breaks = dec.replace(/(?:\r\n|\r|\n)/g, '<br>');
    var ix = flags.length;
    if (ix > 2) {
        var obj = JSON.parse(flags);
        var s_flags = "";
        for(var prop in obj) {
            s_flags += "<br><b>("+obj[prop]+") Reported FLAGs for :</b> " + prop;
        }
        breaks += s_flags;
    }
    
    var list = document.getElementById('feed_update_list');
    var entry = document.createElement('li');
    if (approved === "Y") {
        var text = "BAN";
    } else {
        var text = "UN-BAN";
    }
    var reporting_abuse = "<br><span id='ban-"+id+"'></span><button onclick='ban(\""+id+"\", this, \""+approved+"\");'>"+text+"</button>";
    breaks += reporting_abuse;
    
    entry.innerHTML = breaks.trim();
    list.appendChild(entry);
    var hr = document.createElement('hr');
    list.appendChild(hr);
}
function ban(id, me, approved) {
    if (approved === "Y") {
        var text = "BAN";
        var toggle = "N";
    } else {
        var text = "UN-BAN";
        var toggle = "Y";
    }
    var x = confirm("Are you sure you want to "+text+" this message?");
    if (x === true) {
        postAjax("ban.php", { approved: toggle, id: id }, function(data) {
            alert(data);
        });
        document.getElementById("ban-"+id).innerHTML = "Has Been "+text+"NED!";
        me.style.display = "none";
    }
}

<?php    
foreach($results->data as $row) {
    echo "grab_text('{$row['cypher']}', '{$row['id']}', '{$row['flags']}', '{$row['approved']}');";
}
?>
</script>

<a href="?see_banned=<?= $approved ?>">View only <?= ($approved === "N") ? "Approved" : "BANNED"; ?></a>
&nbsp; &nbsp; <a href="?see_banned=all">View ALL</a>&nbsp; &nbsp; 
<?php

echo $links;

require_once 'end_html.php';