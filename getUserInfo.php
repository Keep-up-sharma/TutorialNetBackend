<?php

header("content-type:Application/json");
require('config.php');
header("Access-Control-Allow-Credentials: true");
session_start();

echo json_encode([
    'name' => $_SESSION['user']['name'],
    'email' => $_SESSION['user']['email'],
    'username' => $_SESSION['user']['username'],
    'isModerator' => $_SESSION['user']['isModerator'],
]);
?>