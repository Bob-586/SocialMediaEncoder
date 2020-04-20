<?php

require_once 'db.php';

make_session_started();

$cypher = $_POST['enc'] ?? false;
$pass = $_POST['pass'] ?? "N";
$has_passowrd = ($pass === "true") ? "Y" : "N";
$style = $_POST['style'] ?? "";
$tags = $_POST['tags'] ?? "";
$track = $_POST['track'] ?? false;

header('Content-Type: application/json');

rate_limit();

sleep(2);

if ($cypher === false) {
    $data['Failed'] = 'Unable to Save'; 
    echo json_encode($data);
    exit;
}

$safe_cypher = encode_clean($cypher);
$safe_style = strip_tags($style); // Don't use encode_clean as quotes are needed for it to work
$safe_tags = encode_clean($tags);
$pdo = get_db();

try {
    $sql = "INSERT INTO `posts` SET `cypher`=:cypher, `has_pwd`=:has_pwd, `style`=:style, `tags`=:tags";
    $pdostmt = $pdo->prepare($sql);
    $pdostmt->bindParam(':cypher', $safe_cypher, \PDO::PARAM_STR);
    $pdostmt->bindParam(':has_pwd', $has_passowrd, \PDO::PARAM_STR);
    $pdostmt->bindParam(':style', $safe_style, \PDO::PARAM_STR);
    $pdostmt->bindParam(':tags', $safe_tags, \PDO::PARAM_STR);
    $pdostmt->execute();
    $id = $pdo->lastInsertId();
} catch (\PDOException $e) {
    $data['Error'] = 'Unable to Save'; 
    echo json_encode($data);
    exit;
}

if ($track === "true") {
    $cookie_name = "sme_posts";
    if(!isset($_COOKIE[$cookie_name])) {
        $cookie_value = "";
    } else {
        $cookie_value = $_COOKIE[$cookie_name] ?? "";
    }
    
    if (! empty($cookie_value)) {
        $a_ids = json_decode(base64_decode($cookie_value), true);
    } else {
        $a_ids = [];
    }
    $a_ids[] = $id;
    
    $b = base64_encode(json_encode($a_ids));
    
    setcookie($cookie_name, $b, time() + (86400 * 30), "/"); // 86400 = 1 day
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