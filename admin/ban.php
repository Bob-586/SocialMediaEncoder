<?php

require_once '../m/db.php';

make_session_started();

header('Content-Type: application/json');

rate_limit("_ban");

sleep(2);

$approved = $_POST['approved'] ?? 'N';
$safe_approved = ($approved === 'N') ? "N" : "Y";
$id = $_REQUEST['id'] ?? false;

if ($id === false || $id === '') {
    echo json_encode(["Failed" => "Error"]);
    exit;    
}

$safe_id = encode_clean($id);
if (! filter_var($safe_id, FILTER_VALIDATE_INT)) {
    echo json_encode(["Failed" => "Error"]);
    exit;     
}

$is_a_admin = has_role('admin');
if (! $is_a_admin) {
    echo json_encode(["Failed" => "Error"]);
    exit;   
}

$pdo = get_db();
try {
    $sql = "UPDATE `posts` SET `approved`=:approved WHERE `id`=:id LIMIT 1";
    $pdostmt = $pdo->prepare($sql);
    $pdostmt->bindParam(':id', $safe_id, \PDO::PARAM_INT);
    $pdostmt->bindParam(':approved', $safe_approved, \PDO::PARAM_STR);
    $pdostmt->execute();
    $count = $pdo->rowCount();
} catch (\PDOException $e) {
    $data['Error'] = 'Unable to Ban'; 
    echo json_encode($data);
    exit;
}  

$data['Success'] = "Banned...";
$data['count'] = $count;
echo json_encode($data);