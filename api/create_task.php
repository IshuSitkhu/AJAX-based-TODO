<?php
include '../auth/auth.php';
include '../config/db.php';

header('Content-Type: application/json');

if ($_SESSION['role'] != 'admin') {
    echo json_encode(["status"=>"error"]);
    exit();
}

$task = $_POST['task'];
$user_id = $_POST['user_id'];
$assigned_by = $_SESSION['user_id'];

$stmt = $conn->prepare("INSERT INTO todos (task, user_id, assigned_by, status) VALUES (?, ?, ?, 'pending')");
$stmt->bind_param("sii", $task, $user_id, $assigned_by);

echo json_encode([
    "status" => $stmt->execute() ? "success" : "error"
]);