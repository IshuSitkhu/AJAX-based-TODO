<?php
include '../config/db.php';
include '../auth/auth.php';

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'];

// get tasks assigned to this user
$stmt = $conn->prepare("
    SELECT pt.id, pt.task, pt.status, p.title AS project_name
    FROM project_tasks pt
    JOIN projects p ON p.id = pt.project_id
    WHERE pt.assigned_user_id = ?
");

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

$tasks = [];

while ($row = $result->fetch_assoc()) {
    $tasks[] = $row;
}

echo json_encode($tasks);
?>