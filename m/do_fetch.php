<?php

sleep(2);

require_once 'db.php';

$id = $_POST['id'] ?? false;
if ($id === false || $id === '') {
    echo json_encode(["Failed" => "Error"]);
    exit;    
}
try {
    $sql = "SELECT `cypher`, `has_pwd`, `ts`, `tags` FROM `posts` WHERE `approved`='Y' && `id`=:id LIMIT 1";
    $pdostmt = $pdo->prepare($sql);
    $pdostmt->bindParam(':id', $id, \PDO::PARAM_INT);
    $pdostmt->execute();
        
    header('Content-Type: application/json');
    echo json_encode($pdostmt->fetch(\PDO::FETCH_OBJ));
} catch (\PDOException $e) {
    echo json_encode(["Failed" => "Error"]);
    exit;
}  