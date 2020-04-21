<?php

sleep(1);

require_once 'db.php';

header('Content-Type: application/json');

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
    $sql = "SELECT `approved`, `flags` FROM `posts` WHERE `id`=:id LIMIT 1";
    $pdostmt = $pdo->prepare($sql);
    $pdostmt->bindParam(':id', $safe_id, \PDO::PARAM_INT);
    $pdostmt->execute();
    
    $count = $pdostmt->rowCount();
    if ($count === 0) {
        echo json_encode(["Failed" => "No Message Found!"]);
        exit;     
    }
    
    $obj = $pdostmt->fetch(\PDO::FETCH_OBJ);
    if ($obj->approved === "N") {
        echo json_encode($obj);
        exit;
    }
    
    $sql = "SELECT `id`, `cypher`, `has_pwd`, `tags`, `style`, DATE_FORMAT(ts, '%y-%c-%e-%H-%i') as ds FROM `posts` WHERE `id`=:id LIMIT 1";
    $pdostmt = $pdo->prepare($sql);
    $pdostmt->bindParam(':id', $safe_id, \PDO::PARAM_INT);
    $pdostmt->execute();
    $obj = $pdostmt->fetch(\PDO::FETCH_OBJ);
    echo json_encode($obj);
} catch (\PDOException $e) {
    echo json_encode(["Failed" => "Error"]);
    exit;
}  