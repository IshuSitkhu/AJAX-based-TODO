<?php
include '../config/db.php';
include '../auth/auth.php';

header('Content-Type: application/json');

if ($_SESSION['role'] != 'admin') {
    echo json_encode(["status" => "error", "message" => "Access denied"]);
    exit();
}

$id = $_POST['id'];
$task = $_POST['task'];
$status = $_POST['status'] ?? 'pending';

$stmt = $conn->prepare("
    UPDATE project_tasks 
    SET task = ?, status = ?
    WHERE id = ?
");

$stmt->bind_param("ssi", $task, $status, $id);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Project task updated"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Update failed"
    ]);
}
?>