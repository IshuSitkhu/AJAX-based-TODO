<?php
include '../config/db.php';

header('Content-Type: application/json');

$project_id = $_POST['project_id'];
$task = $_POST['task'];
$assigned_user_id = $_POST['assigned_user_id'];

// insert directly into project_tasks
$stmt = $conn->prepare("
    INSERT INTO project_tasks (project_id, task, assigned_user_id, status)
    VALUES (?, ?, ?, 'pending')
");

$stmt->bind_param("isi", $project_id, $task, $assigned_user_id);

if ($stmt->execute()) {

    echo json_encode([
        "status" => "success",
        "message" => "Task assigned successfully"
    ]);

} else {

    echo json_encode([
        "status" => "error",
        "message" => "Failed to create task"
    ]);
}
?>