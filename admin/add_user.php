<?php

require_once '../m/db.php';
require_once 'start_html.php';

make_session_started();
$is_admin = has_role('admin');
if (!$is_admin) {
	echo "Access Denied";
	exit; // HINT: Comment out to add first admin user, then re-enable
}

function add_user(array $a): void {
    $pdo = get_db();
    try {
        $password = $a['password'] ?? '';
        $user_name = $a['user_name'] ?? '';
        if (empty($password) || empty($user_name)) {
            throw new \Exception('password, user_name was empty');
        }
        
        $db_pwd_hash = password_hash($password, PASSWORD_BCRYPT);
        
        $sql = "INSERT INTO `admin_users` SET `username`=:user_name, `password`=:password";
        $pdostmt = $pdo->prepare($sql);
        $pdostmt->bindParam(':user_name', $user_name, \PDO::PARAM_STR);       
        $pdostmt->bindParam(':password', $db_pwd_hash, \PDO::PARAM_STR);
        $pdostmt->execute();
        
        $lock = "admin.lock";
        touch($lock);
        echo 'Added new User!<br/>';
    } catch (\PDOException $e) {
        echo $e->getMessage();
    }    
}


$save = $_POST['save'] ?? false;
if ($save) {
    echo "Attempting to add user" . PHP_EOL;
    try {
        $user_name = $_POST['user_name'] ?? '';
        $password = $_POST['password'] ?? '';
        
        add_user( [
            'user_name'=>$user_name,
            'password'=>$password
        ] ); 
    } catch (\Exception $e) {
        echo $e->getMessage();
        require_once 'end_html.php';
        exit;
    }
} else {
    ?>
        <form method="POST">
            <label for="user_name">User Name</label>
            <input type="text" id="user_name" name="user_name" />
            <br/><br/>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" />
            <br/>
<!--            <label for="user_name">First Name</label>
            <input type="text" id="first_name" name="first_name" />
            <br/>
            <label for="user_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" />-->
            <br/>
            <input type="submit" name="save" />

        </form> 

    <?php
}

require_once 'end_html.php';