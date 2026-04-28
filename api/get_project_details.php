<?php
include '../config/db.php';
include '../auth/auth.php';

header('Content-Type: application/json');

$id = $_GET['id'] ?? 0;

// project
$stmt = $conn->prepare("SELECT * FROM projects WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$project = $stmt->get_result()->fetch_assoc();

// users
$stmt2 = $conn->prepare("
SELECT users.id, users.name 
FROM project_users 
JOIN users ON users.id = project_users.user_id
WHERE project_users.project_id=?
");
$stmt2->bind_param("i", $id);
$stmt2->execute();
$users = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);

// tasks
$stmt3 = $conn->prepare("
SELECT 
    t.id,
    t.task,
    t.status,
    t.created_at,
    u.name AS assigned_user,
    a.name AS assigned_by
FROM project_tasks t
LEFT JOIN users u ON u.id = t.assigned_user_id
LEFT JOIN users a ON a.id = t.assigned_by
WHERE t.project_id=?
");
$stmt3->bind_param("i", $id);
$stmt3->execute();
$tasks = $stmt3->get_result()->fetch_all(MYSQLI_ASSOC);

echo json_encode([
    "project"=>$project,
    "users"=>$users,
    "tasks"=>$tasks
]);