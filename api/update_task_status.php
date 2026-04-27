<?php
include '../config/db.php';
include '../auth/auth.php';

header('Content-Type: application/json');

$id = $_POST['id'];
$status = $_POST['status'];

$stmt = $conn->prepare("
    UPDATE project_tasks 
    SET status=? 
    WHERE id=? AND assigned_user_id=?
");

$stmt->bind_param("sii", $status, $id, $_SESSION['user_id']);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error"]);
}
?>