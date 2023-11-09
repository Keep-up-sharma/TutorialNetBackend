<?php

header("content-type:Application/json");
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
require "db_connect.php";
session_start();


$result = 'fail';

if (isset($_POST["email"])) {

    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $saltedPassword = $email . $_POST["password"] . $email;

    $query = $db->prepare("SELECT * FROM users WHERE email = :email");
    $query->bindValue(":email", $email, PDO::PARAM_STR);
    $query->execute();

    $user = $query->fetch(PDO::FETCH_ASSOC);
    if (password_verify($saltedPassword, $user['hashedPassword'])) {
        $result = 'success';
        $_SESSION['user'] = $user;
    }
}

echo json_encode(["result" => $result, 'session' => session_id()]);


?>