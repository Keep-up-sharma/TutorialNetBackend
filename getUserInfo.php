<?php

header("content-type:Application/json");
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
session_start();

echo json_encode([
    'name' => $_SESSION['user']['name'],
    'email' => $_SESSION['user']['email'],
    'username' => $_SESSION['user']['username'],
]);
?>