<?php

require_once 'db.php';

make_session_started();

$cypher = $_POST['enc'] ?? false;
$pass = $_POST['pass'] ?? "N";
$has_passowrd = ($pass === "true") ? "Y" : "N";

header('Content-Type: application/json');

rate_limit();

sleep(2);

if ($cypher === false) {
    $data['Failed'] = 'Unable to Save'; 
    echo json_encode($data);
    exit;
}

$safe_cypher = encode_clean($cypher);

$pdo = get_db();

try {
    $sql = "INSERT INTO `posts` SET `cypher`=:cypher, `has_pwd`=:has_pwd";
    $pdostmt = $pdo->prepare($sql);
    $pdostmt->bindParam(':cypher', $safe_cypher, \PDO::PARAM_STR);
    $pdostmt->bindParam(':has_pwd', $has_passowrd, \PDO::PARAM_STR);
    $pdostmt->execute();
    $id = $pdo->lastInsertId();
} catch (\PDOException $e) {
    $data['Error'] = 'Unable to Save'; 
    echo json_encode($data);
    exit;
}  

try {
    $sql = "SELECT DATE_FORMAT(ts, '%y-%c-%e-%H-%i') as ds FROM `posts` WHERE `id`=:id LIMIT 1";
    $pdostmt = $pdo->prepare($sql);
    $pdostmt->bindParam(':id', $id, \PDO::PARAM_INT);
    $pdostmt->execute();
    $data['ds'] = $pdostmt->fetch(\PDO::FETCH_COLUMN);
} catch (\PDOException $e) {
   
}  
$data['Success'] = "Posted, thank you";
$data['id'] = $id;
echo json_encode($data);