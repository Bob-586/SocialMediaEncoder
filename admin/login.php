<?php

require_once '../m/db.php';
make_session_started();

require_once 'start_html.php';

$user_name = $_POST['user_name'] ?? '';
$password = $_POST['password'] ?? '';

function get_login(string $user_name, string $password): bool {
    $pdo = get_db();
    try {
      $stmt = $pdo->prepare("SELECT `id`, `username`, `password` FROM `admin_users` WHERE `username`=:user_name && `enabled`='Y' LIMIT 1");
      $stmt->bindParam(':user_name', $user_name, \PDO::PARAM_STR);
      $stmt->execute();
      $row_cnt = $stmt->rowCount();
      if ($row_cnt == 1) {
         $row = $stmt->fetch(\PDO::FETCH_ASSOC);
         $is_password_correct = password_verify($password, $row['password']);
         if ($is_password_correct) {
            foreach($row as $field => $value) {
                if ($field === "password") {
                    continue;
                }
                $_SESSION['adm_user_' . $field] = $value;
            } 
            return true;
         }
      }
      return false;
    } catch (\PDOException $e) {
        echo "Opps!";
//      echo $e->getMessage();
      exit;
    }
}


if (! empty($user_name)) {
    $success = get_login($user_name, $password);
    if (! $success) {
        echo 'Denied!';
        require_once 'end_html.php';
        exit;
    }

    $is_a_admin = has_role('admin');
    if (! $is_a_admin) {
        echo "Access Denied!";
        require_once 'end_html.php';
        exit;
    } else {
        ?>
        Welcome Back.
        <a href="index.php">Back to main Page</a>
        <?php
    }

} else {
    ?>
        <form method="POST">
            <label for="user_name">User Name</label>
            <input type="text" id="user_name" name="user_name" />
            
            <label for="password">Password</label>
            <input type="password" id="password" name="password" />
            
            <input type="submit" />

        </form> 
    <?php
}

require_once 'end_html.php';