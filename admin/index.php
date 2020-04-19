<?php

require_once '../m/db.php';
require_once 'start_html.php';

make_session_started();
$is_a_admin = has_role('admin');
if ($is_a_admin) {
    ?>

Main Page Stuff Goes HERE:
    <a href="logout.php">Log out</a><br><br>
    <a href="add_user.php">Add New User</a><br><br>
    <a href="review.php">Review Posts</a>

<?php    
} else {
    ?>
    <a href="login.php">Login Here</a>
    <?php
}

require_once 'end_html.php';