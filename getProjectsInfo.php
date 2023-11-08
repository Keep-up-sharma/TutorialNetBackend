<?php
header("content-type:Application/json");
require "db_connect.php";
$query = $db->prepare("SELECT * FROM projects");
$query->execute();
$projects = $query->fetchAll(PDO::FETCH_ASSOC);

$query = $db->prepare("SELECT * FROM slides ORDER BY project_id,id");
$query->execute();
$slides = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($slides as $slide) {
    for ($i = 0; $i < count($projects); $i++) {
        if ($projects[$i]['id'] == $slide["project_Id"]) {
            $projects[$i]["slides"][] = $slide;
        }
    }
}

echo json_encode($projects);
?>