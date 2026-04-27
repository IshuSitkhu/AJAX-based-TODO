<?php
include '../config/db.php';
include '../auth/auth.php';

header('Content-Type: application/json');

$project_id = $_POST['project_id'];
$user_id = $_POST['user_id'];

// check duplicate
$check = $conn->prepare("SELECT id FROM project_users WHERE project_id=? AND user_id=?");
$check->bind_param("ii", $project_id, $user_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    echo json_encode([
        "status" => "error",
        "message" => "User already assigned"
    ]);
    exit;
}

// insert
$stmt = $conn->prepare("INSERT INTO project_users (project_id, user_id) VALUES (?, ?)");
$stmt->bind_param("ii", $project_id, $user_id);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "User added to project"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to add user"
    ]);
}
?>