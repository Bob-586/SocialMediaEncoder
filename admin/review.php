<?php
require_once 'start_html.php';
?>
<script type="text/javascript" src="../dist/all.min.js?v=1.3"></script>
<?php
require_once '../m/db.php';
require_once '../m/paginate.php';

$pdo = get_db();

$sql = "SELECT `id`, `cypher`, `flags` FROM `posts` WHERE `approved`='Y' && `has_pwd`='N' ORDER BY `ts` DESC";
$limit = $_GET['limit'] ?? 10;
$page = $_GET['page'] ?? 1;
$pag = new paginate($pdo, $sql);
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
function grab_text(data, id, flags) { 
    var dec = do_dec(data, ''); 
    var breaks = dec.replace(/(?:\r\n|\r|\n)/g, '<br>');
    var ix = flags.length;
    if (ix > 2) {
        var obj = JSON.parse(flags);
        var s_flags = "";
        for(var prop in obj) {
            s_flags += "<br><br><b>Reported FLAGs :</b> " + prop + "<br><br>";
        }
        breaks += s_flags;
    }
    
    var list = document.getElementById('feed_update_list');
    var entry = document.createElement('li');
    
    var reporting_abuse = "<span id='ban-"+id+"'></span><button onclick='ban(\""+id+"\", this);'>Ban</button>";
    breaks += reporting_abuse;
    
    entry.innerHTML = breaks.trim();
    list.appendChild(entry);
    var hr = document.createElement('hr');
    list.appendChild(hr);
}
function ban(id, me) {
    var x = confirm("Are you sure you want to BAN this message?");
    if (x === true) {
        postAjax("ban.php", { approved: 'N', id: id }, function(data) {
            alert(data);
        });
        document.getElementById("ban-"+id).innerHTML = "Has Been BANNED!";
        me.style.display = "none";
    }
}

<?php    
foreach($results->data as $row) {
    echo "grab_text('{$row['cypher']}', '{$row['id']}', '{$row['flags']}');";
}
?>
</script>
<?php

echo $links;

require_once 'start_html.php';