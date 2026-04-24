<?php
include '../config/db.php';

$title = $_POST['title'] ?? '';
$description = $_POST['description'] ?? '';
$users = $_POST['users'] ?? [];

if (!is_array($users)) {
    $users = [$users];
}

// insert project
$stmt = $conn->prepare("INSERT INTO projects (title, description) VALUES (?, ?)");
$stmt->bind_param("ss", $title, $description);
$stmt->execute();

$project_id = $stmt->insert_id;

// assign users (if you have pivot table)
foreach ($users as $user_id) {
    $stmt2 = $conn->prepare("INSERT INTO project_users (project_id, user_id) VALUES (?, ?)");
    $stmt2->bind_param("ii", $project_id, $user_id);
    $stmt2->execute();
}

echo json_encode([
    "status" => "success",
    "message" => "Project created"
]);
?>