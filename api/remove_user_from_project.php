<?php
include '../config/db.php';
include '../auth/auth.php';

header('Content-Type: application/json');

$project_id = $_POST['project_id'];
$user_id = $_POST['user_id'];

$stmt = $conn->prepare("
    DELETE FROM project_users 
    WHERE project_id = ? AND user_id = ?
");

$stmt->bind_param("ii", $project_id, $user_id);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "User removed from project"
    ]);
} else {
    echo json_encode([
        "status" => "error"
    ]);
}
?>