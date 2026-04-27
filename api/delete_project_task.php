<?php
include '../config/db.php';
include '../auth/auth.php';

header('Content-Type: application/json');

if ($_SESSION['role'] != 'admin') {
    echo json_encode(["status" => "error"]);
    exit();
}

$id = $_POST['id'];

$stmt = $conn->prepare("DELETE FROM project_tasks WHERE id=?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error"]);
}
?>