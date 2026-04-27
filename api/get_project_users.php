<?php
include '../config/db.php';
include '../auth/auth.php';

header('Content-Type: application/json');

$project_id = $_GET['project_id'] ?? 0;

if (!$project_id) {
    echo json_encode([]);
    exit;
}

$stmt = $conn->prepare("
    SELECT u.id, u.name
    FROM project_users pu
    JOIN users u ON u.id = pu.user_id
    WHERE pu.project_id = ?
");

$stmt->bind_param("i", $project_id);
$stmt->execute();

$result = $stmt->get_result();

$users = [];

while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

echo json_encode($users);
?>