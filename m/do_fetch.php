<?php

sleep(2);

require_once 'db.php';

$id = $_POST['id'] ?? false;
if ($id === false || $id === '') {
    echo json_encode(["Failed" => "Error"]);
    exit;    
}

$safe_id = encode_clean($id);
if (! filter_var($safe_id, FILTER_VALIDATE_INT)) {
    echo json_encode(["Failed" => "Error"]);
    exit;     
}

$pdo = get_db();

try {
    $sql = "SELECT `cypher`, `has_pwd`, `tags`, DATE_FORMAT(ts, '%y-%c-%e-%H-%i') as ds FROM `posts` WHERE `approved`='Y' && `id`=:id LIMIT 1";
    $pdostmt = $pdo->prepare($sql);
    $pdostmt->bindParam(':id', $safe_id, \PDO::PARAM_INT);
    $pdostmt->execute();
        
    header('Content-Type: application/json');
    echo json_encode($pdostmt->fetch(\PDO::FETCH_OBJ));
} catch (\PDOException $e) {
    echo json_encode(["Failed" => "Error"]);
    exit;
}  