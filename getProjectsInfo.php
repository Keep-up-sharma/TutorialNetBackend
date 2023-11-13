<?php
header("content-type:Application/json");
header("Access-Control-Allow-Origin: *");
require "db_connect.php";
$statement;
if (isset($_GET["sortby"])) {
    switch ($_GET["sortby"]) {
        case 'name':
            $statement = "SELECT *, (SELECT categories.name FROM categories WHERE id = projects.category_id) as 'category' FROM projects ORDER BY projects.title ASC";
            break;
        case 'date':
            $statement = "SELECT *, (SELECT categories.name FROM categories WHERE id = projects.category_id) as 'category' FROM projects ORDER BY projects.uploadDate DESC";
            break;
        case 'creator':
            $statement = "SELECT *, (SELECT categories.name FROM categories WHERE id = projects.category_id) as 'category' FROM projects ORDER BY projects.creator ASC";
            break;
        default:
            $statement = "SELECT *, (SELECT categories.name FROM categories WHERE id = projects.category_id) as 'category' FROM projects";
            break;
    }
} else {
    $statement = "SELECT *, (SELECT categories.name FROM categories WHERE id = projects.category_id) as 'category' FROM projects";
}
$query = $db->prepare($statement);
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