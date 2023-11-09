<?php
header("content-type:Application/json");
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
require "db_connect.php";
session_start();

$result = 'fail';

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    if (isset($_FILES['thumbnail'])) {
        $file = $_FILES['thumbnail'];
        $filename = 'uploads/' . time() . $_FILES['thumbnail']['name'];
        if (move_uploaded_file($file['tmp_name'], $filename)) {
            $query = $db->prepare('INSERT INTO PROJECTS (`Creator`,`Title`,`Description`,`thumbnailUrl`) VALUES (:username,:title,:description,:thumbnailUrl)');
            $query->bindValue(':username', $user['username'], PDO::PARAM_STR);
            $query->bindValue(':title', $title, PDO::PARAM_STR);
            $query->bindValue(':description', $description, PDO::PARAM_STR);
            $query->bindValue(':thumbnailUrl', $filename);
            $query->execute();
            $result = 'success';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
    }
}
echo json_encode(['result'=>$result]);
?>
