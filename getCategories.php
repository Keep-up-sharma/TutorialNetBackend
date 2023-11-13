<?php
header("content-type:Application/json");
header("Access-Control-Allow-Origin: *");
require "db_connect.php";
$query = $db->prepare("SELECT * FROM categories");
$query->execute();
$categories = $query->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($categories);
?>