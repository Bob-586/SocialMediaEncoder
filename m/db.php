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

function rate_limit(string $thing = "", int $max_seconds = 12) {
	if (isset($_SESSION['LAST_CALL' . $thing])) {
		$last = strtotime($_SESSION['LAST_CALL'. $thing]);
		$curr = strtotime(date("Y-m-d h:i:s"));
		$sec =  abs($last - $curr);
		if ($sec <= $max_seconds) { // rate limit
		  $data['Error'] = 'Rate Limit Exceeded'; 
		  echo json_encode($data);
		  exit;	
		}
	}
	$_SESSION['LAST_CALL' . $thing] = date("Y-m-d h:i:s");
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
    
    if ($id > 0) {
        return true; // Admin, found
    }
    $lock = "admin.lock";
    if(file_exists($lock)) {
        return false; // No Admin found
    }
    
    $sql = "SELECT `id` FROM `admin_users` WHERE `enabled`='Y' LIMIT 1";
    try {
      $pdo = get_db();  
      $stmt = $pdo->prepare($sql);
      $stmt->execute();
      $row_cnt = $stmt->rowCount();
      if ($row_cnt === 0) {
          return true; // Allow, access as NO-USER is setup nor enabled
      }
      return false; // No Admin found
    } catch (\PDOException $e) {
      echo "Opps!";
//      echo $e->getMessage();
      exit;
    }
    
}