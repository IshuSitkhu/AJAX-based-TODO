<?php
include '../config/db.php';

header('Content-Type: application/json');

$project_id = $_POST['project_id'];
$task = $_POST['task'];
$assigned_user_id = $_POST['assigned_user_id'];

// CHECK USER EXISTS IN PROJECT
$check = $conn->prepare("
    SELECT id FROM project_users 
    WHERE project_id=? AND user_id=?
");
$check->bind_param("ii", $project_id, $assigned_user_id);
$check->execute();
$res = $check->get_result();

if ($res->num_rows == 0) {
    echo json_encode([
        "status" => "error",
        "message" => "User not assigned to project"
    ]);
    exit();
}

$stmt = $conn->prepare("
    INSERT INTO project_tasks 
    (project_id, task, assigned_user_id, assigned_by, status)
    VALUES (?, ?, ?, ?, 'pending')
");

$stmt->bind_param("isii", $project_id, $task, $assigned_user_id, $_SESSION['user_id']);

// EXECUTE
if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Task assigned successfully"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => $stmt->error
    ]);
}
?>