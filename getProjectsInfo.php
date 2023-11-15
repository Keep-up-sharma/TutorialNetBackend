<?php
header("content-type:Application/json");
header("Access-Control-Allow-Origin: *");
require "db_connect.php";
$statement = "SELECT *, categories.name as 'category',(SELECT COUNT(*) FROM projects) as 'total' FROM projects INNER JOIN categories ON projects.category_id = categories.id ";

$filter = filter_input(INPUT_GET, "filter", FILTER_SANITIZE_STRING);
$category = filter_input(INPUT_GET, "category", FILTER_SANITIZE_STRING);

if ($filter && !$category) {
    $statement = "SELECT *, categories.name as 'category',(SELECT COUNT(*) FROM projects  WHERE projects.title LIKE :filter) as 'total' FROM projects INNER JOIN categories ON projects.category_id = categories.id  WHERE projects.title LIKE :filter";
} else if ($filter && $category) {
    $statement = "SELECT *, categories.name as 'category',(SELECT COUNT(*) FROM projects INNER JOIN categories ON projects.category_id = categories.id  WHERE projects.title LIKE :filter AND categories.name = :category) as 'total' FROM projects INNER JOIN categories ON projects.category_id = categories.id   WHERE projects.title LIKE :filter AND categories.name = :category";
}elseif ($category) {
    $statement = "SELECT *, categories.name as 'category',(SELECT COUNT(*) FROM projects INNER JOIN categories ON projects.category_id = categories.id WHERE categories.name = :category) as 'total' FROM projects INNER JOIN categories ON projects.category_id = categories.id   WHERE categories.name = :category";
}

if (isset($_GET["sortby"])) {
    switch ($_GET["sortby"]) {
        case 'name':
            $statement .= " ORDER BY projects.title ASC";
            break;
        case 'date':
            $statement .= " ORDER BY projects.uploadDate DESC";
            break;
        case 'creator':
            $statement .= " ORDER BY projects.creator ASC";
            break;
        default:
            break;
    }
}

$limit = filter_input(INPUT_GET, "limit", FILTER_SANITIZE_NUMBER_INT);
$offset = filter_input(INPUT_GET, "offset", FILTER_SANITIZE_NUMBER_INT);

$statement .= " LIMIT :limit OFFSET :offset";

$query = $db->prepare($statement);
$query->bindValue(":limit", $limit, PDO::PARAM_INT);
$query->bindValue(":offset", $offset, PDO::PARAM_INT);


if ($filter) {
    $query->bindValue(":filter", $filter);
}
if ($category) {
    $query->bindValue(":category", $category);
}
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