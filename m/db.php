<?php

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

} catch (\PDOException $e) {
    echo $e->getMessage();
    exit;
}

function encode_clean(string $data): string {
    return htmlentities(trim($data), ENT_QUOTES, 'UTF-8');
}