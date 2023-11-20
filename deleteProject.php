<?php

header("content-type:Application/json");
require('config.php');
header("Access-Control-Allow-Credentials: true");
session_start();

if (!isset($_SESSION['user']['username'])) {
    echo json_encode(['success' => false, 'error' => 'not authorized']);
    die();
}

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'error' => 'not found']);
    die();
}

$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
require "db_connect.php";
$query = $db->prepare("DELETE FROM projects WHERE id=:id AND creator=:user");
$query->bindValue(":id", $id);
$query->bindValue(":user", $_SESSION['user']['username']);
$query->execute();
echo json_encode(array('success' => true));
?>