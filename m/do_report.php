<?php
header('Content-Type: application/json');

require_once 'db.php';

make_session_started();

rate_limit("_abuse", 200);

sleep(2);

$max_allowed_strikes = 5;

$id = $_POST['id'] ?? false;
if ($id === false || $id === '') {
    $data['Failed'] = 'Opps!'; 
    echo json_encode($data);
    exit;  
}

$safe_id = encode_clean($id);
if (! filter_var($safe_id, FILTER_VALIDATE_INT)) {
    $data['Failed'] = 'Opps!'; 
    echo json_encode($data);
    exit;  
}

$flag = $_POST['flag'] ?? false;
if ($flag === false || $flag === '') {
    $data['Failed'] = 'No Flags'; 
    echo json_encode($data);
    exit;   
}
$safe_flag = encode_clean($flag);

$pdo = get_db();

try {
    $sql = "SELECT `flags`, `approved` FROM `posts` WHERE `id`=:id LIMIT 1";
    $pdostmt = $pdo->prepare($sql);
    $pdostmt->bindParam(':id', $safe_id, \PDO::PARAM_INT);
    $pdostmt->execute();
    $count = $pdostmt->rowCount();
    if ($count === 1) {
        $cm = $pdostmt->fetch(\PDO::FETCH_OBJ);
        if ($cm->approved === "N") { // Don't HINT at LIMIT
           $data['Success'] = 'Marked for Abuse, this will be reviewed by admins!'; 
           echo json_encode($data);
           exit;   
        }
        $flags = (! empty($cm->flags)) ? json_decode($cm->flags, false) : new stdClass;
    } else {
        $data['Failed'] = 'Post was Removed!'; 
        echo json_encode($data);
        exit; 
    }
} catch (\PDOException $e) {
    $data['Failed'] = 'Opps!'; 
    echo json_encode($data);
    exit; 
}  

$a_flags = $flags;
$a_flags->$safe_flag = ($flags->$safe_flag) ? $flags->$safe_flag + 1 : 1;
$safe_flags = json_encode($a_flags);

$approved = ($a_flags->$flag > $max_allowed_strikes) ? "N" : "Y";
try {
    $sql = "UPDATE `posts` SET `flags`=:flags, `approved`=:approved WHERE `id`=:id LIMIT 1";
    $pdostmt = $pdo->prepare($sql);
    $pdostmt->bindParam(':flags', $safe_flags, \PDO::PARAM_STR);
    $pdostmt->bindParam(':approved', $approved, \PDO::PARAM_STR);
    $pdostmt->bindParam(':id', $safe_id, \PDO::PARAM_INT);
    $pdostmt->execute();
} catch (\PDOException $e) {
    $data['Failed'] = 'Opps!'; 
    echo json_encode($data);
    exit;  
}  

$data['Success'] = "Marked for Abuse, this will be reviewed by admins!";
echo json_encode($data);