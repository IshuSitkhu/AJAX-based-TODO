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

    // 2. clean relations (important)
    $conn->query("DELETE FROM project_users WHERE project_id = $id");
    $conn->query("DELETE FROM project_tasks WHERE project_id = $id");

    echo json_encode([
        "status" => "success"
    ]);
} else {
    echo json_encode([
        "status" => "error"
    ]);
}
?>