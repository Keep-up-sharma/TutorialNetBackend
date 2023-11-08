<?php
header("content-type:Application/json");
header("Access-Control-Allow-Origin: *");
if (!isset($_GET["id"])) {
    echo "[]";
    die();
}

$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
require "db_connect.php";
$query = $db->prepare("SELECT * FROM projects WHERE id=:id");
$query->bindValue(":id",$id);
$query->execute();
$projects = $query->fetch(PDO::FETCH_ASSOC);

$query = $db->prepare("SELECT * FROM slides WHERE project_id=:id ORDER BY id");
$query->bindValue(":id",$id);
$query->execute();
$slides = $query->fetchAll(PDO::FETCH_ASSOC);
$projects['slides'] = $slides;
echo json_encode($projects);
?>