<?php

require_once 'db.php';

make_session_started();

rate_limit();

sleep(2);

$id = $_POST['id'] ?? false;
if ($id === false || $id === '') {
    echo "Failure";
    exit;    
}

$safe_id = encode_clean($id);
if (! filter_var($safe_id, FILTER_VALIDATE_INT)) {
    echo "Failure";
    exit;     
}

$flag = $_POST['flag'] ?? false;
if ($flag === false || $flag === '') {
    echo "Failure";
    exit;    
}

$pdo = get_db();

try {
    $sql = "SELECT `flags` FROM `posts` WHERE `approved`='Y' && `id`=:id LIMIT 1";
    $pdostmt = $pdo->prepare($sql);
    $pdostmt->bindParam(':id', $safe_id, \PDO::PARAM_INT);
    $pdostmt->execute();
    $count = $pdostmt->rowCount();
    if ($count === 1) {
        $cm = $pdostmt->fetch(\PDO::FETCH_COLUMN);
        $flags = (! empty($cm)) ? json_decode($cm, false) : new stdClass;
    } else {
        $flags = new stdClass;
    }
} catch (\PDOException $e) {
    $flags = new stdClass;
}  

$a_flags = $flags;
$a_flags->$flag = true;
$safe_flags = json_encode($a_flags);
try {
    $sql = "UPDATE `posts` SET `flags`=:flags WHERE `id`=:id LIMIT 1";
    $pdostmt = $pdo->prepare($sql);
    $pdostmt->bindParam(':flags', $safe_flags, \PDO::PARAM_STR);
    $pdostmt->bindParam(':id', $safe_id, \PDO::PARAM_INT);
    $pdostmt->execute();
} catch (\PDOException $e) {
    echo "Failure";
    exit;
}  

echo "Marked for Abuse, this will be reviewed by admins!";