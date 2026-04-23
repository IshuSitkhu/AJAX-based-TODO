<?php
include '../auth/auth.php';
include '../config/db.php';

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT t.*, u.name AS admin_name
    FROM todos t
    LEFT JOIN users u ON t.assigned_by = u.id
    WHERE t.user_id=?
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