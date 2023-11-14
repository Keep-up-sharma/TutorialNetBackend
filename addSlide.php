<?php
session_start();
header("content-type:Application/json");
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
require "db_connect.php";
$result = 'fail';

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $projectId = filter_var($_POST['projectId'], FILTER_VALIDATE_INT);
    $content = filter_var($_POST['content'], FILTER_UNSAFE_RAW);
    $num = filter_var($_POST['num'], FILTER_VALIDATE_INT);
    $query = $db->prepare("SELECT creator from PROJECTS WHERE id=:projectId");
    $query->bindValue(':projectId', $projectId, PDO::PARAM_INT);
    $query->execute();
    $project = $query->fetch(PDO::FETCH_ASSOC);

    if ($project['creator'] == $user['username']) {


        $query = $db->prepare("INSERT INTO `slides`(`num`, `title`, `content`, `project_Id`) VALUES (:num,:title,:content,:projectId)");
        $query->bindValue(':title', $title, PDO::PARAM_STR);
        $query->bindValue(':projectId', $projectId, PDO::PARAM_INT);
        $query->bindValue(':content', $content, PDO::PARAM_STR);
        $query->bindValue(':num', $num, PDO::PARAM_INT);


        $query->execute();
        $result = 'success';
    }
}

echo json_encode(['result' => $result]);
?>