<?php
include '../config/db.php';
include '../auth/auth.php';

header('Content-Type: application/json');

if ($_SESSION['role'] != 'admin') {
    echo json_encode(["status" => "error"]);
    exit();
}

$id = $_POST['id'];

// 1. delete project
$stmt = $conn->prepare("DELETE FROM projects WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {

    $stmt2 = $conn->prepare("DELETE FROM project_users WHERE project_id = ?");
    $stmt2->bind_param("i", $id);
    $stmt2->execute();

    $stmt3 = $conn->prepare("DELETE FROM project_tasks WHERE project_id = ?");
    $stmt3->bind_param("i", $id);
    $stmt3->execute();
    echo json_encode([
        "status" => "success"
    ]);
} else {
    echo json_encode([
        "status" => "error"
    ]);
}
?>