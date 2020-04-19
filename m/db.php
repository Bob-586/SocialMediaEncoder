<?php

function get_db(bool $show_errors = false) {
    $db_info = [
          'TYPE' => getenv('m_db_type'),
          'HOST' => getenv('m_db_host'),
          'PORT' => getenv('m_db_port'),
          'NAME' => getenv('m_db_name'),
          'USER' => getenv('m_db_user'),
          'PASS' => getenv('m_db_pass')
    ];

    $options = array(
      \PDO::ATTR_PERSISTENT => false,
      \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
      \PDO::ATTR_TIMEOUT => 6,
    );

    $pdo_set_time = true;

    try {

      $dsn = $db_info['TYPE'] . ':host=' . $db_info['HOST'] . ';port=' . $db_info['PORT'] . ';dbname=' . $db_info['NAME'];

      $pdo = new \PDO($dsn, $db_info['USER'], $db_info['PASS'], $options);

      if ($pdo_set_time === true) {
        $pdo->query("SET NAMES 'utf8';");
        $pdo->query("SET CHARACTER SET utf8;");
        $pdo->query("SET CHARACTER_SET_CONNECTION=utf8;");
        $pdo->query("SET SQL_MODE = '';");
        $pdo->query("SET time_zone = '+00:00';");
      }
      return $pdo;
    } catch (\PDOException $e) {
        if ($show_errors) {
            echo $e->getMessage();
        } else {
            echo "Bad Error";
        }
        exit;
    }
}

function encode_clean(string $data): string {
    return htmlentities(trim($data), ENT_QUOTES, 'UTF-8');
}

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

function make_session_started() {
  if ((function_exists('session_status') && session_status() !== PHP_SESSION_ACTIVE) || !session_id()) {
    session_name('m_');
    if (! headers_sent()) {
//      session_set_cookie_params(0, '/');
        session_start();
    }
  }
}

function has_role(string $role = 'admin'): bool {
    $user = ($role === "admin") ? "adm_user_id" : "user_id";
    $id = $_SESSION[$user] ?? 0;
    return ($id > 0) ? true : false;
}