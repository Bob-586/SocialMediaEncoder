<?php

sleep(2);

require_once 'db.php';

$page = $_POST['page'] ?? 1;
$limit = $_POST['limit'] ?? 2;
if (! filter_var($page, FILTER_VALIDATE_INT)) {
    $page = 1;
}
if (! filter_var($limit, FILTER_VALIDATE_INT)) {
    $limit = 2;
}
if ($limit > 11) {
    $limit = 10; // Don't go nutts with Cypher-Text!
}

$pdo = get_db();

try {
    $sql = "SELECT `id`, `cypher`, `tags`, `style`, DATE_FORMAT(ts, '%y-%c-%e-%H-%i') as ds FROM `posts` WHERE `approved`='Y' && `has_pwd`='N' ORDER BY `ts` DESC LIMIT " . ( ( $page - 1 ) * $limit ) . ", {$limit};";
    $pdostmt = $pdo->prepare($sql);
    $pdostmt->execute();
    $count = $pdostmt->rowCount();    
    header('Content-Type: application/json');
    echo ($count) ? json_encode($pdostmt->fetchAll(\PDO::FETCH_OBJ)) : json_encode(false);
} catch (\PDOException $e) {
    echo json_encode(["Failed" => "Error"]);
    exit;
}  