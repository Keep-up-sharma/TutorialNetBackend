<?php

session_start();
header("content-type:Application/json");
require('config.php');
header("Access-Control-Allow-Credentials: true");

echo json_encode([
    'name' => $_SESSION['user']['name'],
    'email' => $_SESSION['user']['email'],
    'username' => $_SESSION['user']['username'],
    'isModerator' => $_SESSION['user']['isModerator'],
]);
?>