<?php
include '../auth/auth.php';
include '../config/db.php';

header('Content-Type: application/json');

$id = $_POST['id'];
$task = $_POST['task'];

$stmt = $conn->prepare("UPDATE todos SET task=? WHERE id=?");
$stmt->bind_param("si", $task, $id);

echo json_encode([
    "status" => $stmt->execute() ? "success" : "error"
]);