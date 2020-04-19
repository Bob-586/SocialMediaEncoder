<?php

require_once '../m/db.php';

make_session_started();

session_unset();
session_destroy();

echo "You have been Logged out now.";