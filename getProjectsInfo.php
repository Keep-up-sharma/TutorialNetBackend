<?php
header("content-type:Application/json");
header("Access-Control-Allow-Origin: *");
require "db_connect.php";
$query = $db->prepare("SELECT *, (SELECT categories.name FROM categories WHERE id = projects.category_id) as 'category' FROM projects");
$query->execute();
$projects = $query->fetchAll(PDO::FETCH_ASSOC);
$query = $db->prepare("SELECT * FROM slides ORDER BY project_id,num");
$query->execute();
$slides = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($slides as $slide) {
    for ($i = 0; $i < count($projects); $i++) {
        if ($projects[$i]['id'] == $slide["project_Id"]) {
            $projects[$i]["slides"][] = $slide;
        }
    }
}

for ($i = 0; $i < count($projects); $i++) {
    if (!isset($projects[$i]["slides"])) {
        $projects[$i]["slides"] = [];
    }
}

echo json_encode($projects);
?>