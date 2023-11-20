<?php


header("content-type:Application/json");
require('config.php');
header("Access-Control-Allow-Credentials: true");

require "db_connect.php";
session_start();

$result = 'fail';

if (isset($_POST["name"])) {

    $name = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $username = filter_var($_POST["username"], FILTER_SANITIZE_STRING);
    $saltedPassword = $email . $_POST["password"] . $email;
    $hashedPassword = password_hash($saltedPassword, PASSWORD_DEFAULT);

    $query = $db->prepare("INSERT INTO `users`( `username`, `email`, `hashedPassword`, `name`) VALUES (:username,:email,:hashedPassword,:name)");
    $query->bindValue(":email", $email, PDO::PARAM_STR);
    $query->bindValue(":username", $username, PDO::PARAM_STR);
    $query->bindValue(":name", $name, PDO::PARAM_STR);
    $query->bindValue(":hashedPassword", $hashedPassword, PDO::PARAM_STR);
    $query->execute();
    $result = 'success';

    $query = $db->prepare("SELECT * FROM users WHERE email = :email");
    $query->bindValue(":email", $email, PDO::PARAM_STR);
    $query->execute();

    $user = $query->fetch(PDO::FETCH_ASSOC);
    $_SESSION['user'] = $user;
}

echo json_encode(["result" => $result, 'session' => session_id()]);



?>