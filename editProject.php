<?php
header("content-type:Application/json");
require('config.php');
header("Access-Control-Allow-Credentials: true");
require "db_connect.php";
session_start();

$result = 'fail';

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $projectId = filter_var($_POST['projectId'], FILTER_SANITIZE_NUMBER_INT);
    if (!empty($_FILES['thumbnail']['name']) && getimagesize($_FILES['thumbnail']["tmp_name"])) {
        $file = $_FILES['thumbnail'];
        $filename = 'uploads/' . time() . $_FILES['thumbnail']['name'];
        if (move_uploaded_file($file['tmp_name'], $filename)) {
            // Fetch the current thumbnail URL
            $fetchQuery = $db->prepare('SELECT thumbnailUrl FROM PROJECTS WHERE `Creator` = :username AND `Id` = :projectId');
            $fetchQuery->bindValue(':username', $user['username'], PDO::PARAM_STR);
            $fetchQuery->bindValue(':projectId', $projectId, PDO::PARAM_INT);
            $fetchQuery->execute();
            $fetchResult = $fetchQuery->fetch(PDO::FETCH_ASSOC);
            $oldThumbnailUrl = $fetchResult['thumbnailUrl'];

            // Delete the old thumbnail file
            if (file_exists($oldThumbnailUrl)) {
                unlink($oldThumbnailUrl);
            }

            // Update the project with the new thumbnail
            $query = $db->prepare('UPDATE PROJECTS SET `Title` = :title, `Description` = :description, `thumbnailUrl` = :thumbnailUrl WHERE `Creator` = :username AND `Id` = :projectId');
            $query->bindValue(':username', $user['username'], PDO::PARAM_STR);
            $query->bindValue(':title', $title, PDO::PARAM_STR);
            $query->bindValue(':description', $description, PDO::PARAM_STR);
            $query->bindValue(':thumbnailUrl', $filename);
            $query->bindValue(':projectId', $projectId, PDO::PARAM_INT);
            $query->execute();
            $result = 'success';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
    }else{
        $query = $db->prepare('UPDATE PROJECTS SET `Title` = :title, `Description` = :description WHERE `Creator` = :username AND `Id` = :projectId');
            $query->bindValue(':username', $user['username'], PDO::PARAM_STR);
            $query->bindValue(':title', $title, PDO::PARAM_STR);
            $query->bindValue(':description', $description, PDO::PARAM_STR);
            $query->bindValue(':projectId', $projectId, PDO::PARAM_INT);
            $query->execute();
            $result = 'success';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}
echo json_encode(['result' => $result]);
?>