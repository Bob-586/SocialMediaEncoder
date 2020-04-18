<?php

function make_session_started() {
  if ((function_exists('session_status') && session_status() !== PHP_SESSION_ACTIVE) || !session_id()) {
    session_name('m_');
    if (! headers_sent()) {
//      session_set_cookie_params(0, '/');
        session_start();
    }
  }
}

make_session_started();

$cypher = $_POST['enc'] ?? false;
$pass = $_POST['pass'] ?? "N";
$has_passowrd = ($pass === "true") ? "Y" : "N";

header('Content-Type: application/json');

function rate_limit() {
	if (isset($_SESSION['LAST_CALL'])) {
		$last = strtotime($_SESSION['LAST_CALL']);
		$curr = strtotime(date("Y-m-d h:i:s"));
		$sec =  abs($last - $curr);
		if ($sec <= 12) { // rate limit
		  $data['Error'] = 'Rate Limit Exceeded'; 
		  echo json_encode($data);
		  exit;	
		}
	}
	$_SESSION['LAST_CALL'] = date("Y-m-d h:i:s");
}

rate_limit();

sleep(2);

if ($cypher === false) {
    $data['Failed'] = 'Unable to Save'; 
    echo json_encode($data);
    exit;
}

require_once 'db.php';

try {
    $sql = "INSERT INTO `posts` SET `cypher`=:cypher, `has_pwd`=:has_pwd";
    $pdostmt = $pdo->prepare($sql);
    $pdostmt->bindParam(':cypher', $cypher, \PDO::PARAM_STR);
    $pdostmt->bindParam(':has_pwd', $has_passowrd, \PDO::PARAM_STR);
    $pdostmt->execute();
    $id = $pdo->lastInsertId();
} catch (\PDOException $e) {
    $data['Error'] = 'Unable to Save'; 
    echo json_encode($data);
    exit;
}  
$data['Success'] = "Posted, thank you";
$data['id'] = $id;
echo json_encode($data);