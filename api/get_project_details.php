<?php
include '../config/db.php';
include '../auth/auth.php';

header('Content-Type: application/json');

if ($_SESSION['role'] != 'admin') {
    echo json_encode(["status" => "error"]);
    exit();
}

$project_id = $_GET['id'];

// 1. get project
$sql = "SELECT * FROM projects WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $project_id);
$stmt->execute();
$project = $stmt->get_result()->fetch_assoc();

// 2. get users
$sql2 = "SELECT users.id, users.name 
         FROM project_users
         JOIN users ON users.id = project_users.user_id
         WHERE project_users.project_id = ?";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $project_id);
$stmt2->execute();
$users = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);

// 3. get tasks (assuming tasks table has project_id)
$sql3 = "SELECT id, task FROM tasks WHERE project_id = ?";
$stmt3 = $conn->prepare($sql3);
$stmt3->bind_param("i", $project_id);
$stmt3->execute();
$tasks = $stmt3->get_result()->fetch_all(MYSQLI_ASSOC);

// response
echo json_encode([
    "project" => $project,
    "users" => $users,
    "tasks" => $tasks
]);
?>