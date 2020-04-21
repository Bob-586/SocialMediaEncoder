<?php

require_once 'db.php';
require_once 'paginate.php';
               
$limit = $_REQUEST['limit'] ?? 2;
$page = $_REQUEST['page'] ?? 1;
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

if (isset($_POST['show'])) {
    $pag->set_js_router();
    echo $pag->create_links();
} else {
    $links = $pag->create_links();
}