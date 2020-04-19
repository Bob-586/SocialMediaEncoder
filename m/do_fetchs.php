<?php

sleep(2);

require_once 'db.php';

$pdo = get_db();

try {
    $sql = "SELECT `id`, `cypher`, `tags`, DATE_FORMAT(ts, '%y-%c-%e-%H-%i') as ds FROM `posts` WHERE `approved`='Y' && `has_pwd`='N' ORDER BY `ts` DESC LIMIT 10";
    $pdostmt = $pdo->prepare($sql);
    $pdostmt->execute();
        
    header('Content-Type: application/json');
    echo json_encode($pdostmt->fetchAll(\PDO::FETCH_OBJ));
} catch (\PDOException $e) {
    echo json_encode(["Failed" => "Error"]);
    exit;
}  