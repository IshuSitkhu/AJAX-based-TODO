<?php
include '../config/db.php';
include '../auth/auth.php';

header('Content-Type: application/json');

$id = $_POST['id'];
$status = $_POST['status'];

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// STAFF: can only update their own tasks
if ($role == 'staff') {

    $stmt = $conn->prepare("
        UPDATE project_tasks 
        SET status=? 
        WHERE id=? AND assigned_user_id=?
    ");
    $stmt->bind_param("sii", $status, $id, $user_id);
}

// ADMIN: can update any task
else if ($role == 'admin') {

    $stmt = $conn->prepare("
        UPDATE project_tasks 
        SET status=? 
        WHERE id=?
    ");
    $stmt->bind_param("si", $status, $id);
}

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Task updated"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => $stmt->error
    ]);
}
?>