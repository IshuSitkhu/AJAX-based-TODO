<?php
include '../auth/auth.php';
include '../config/db.php';

$result1 = $conn->query("SELECT COUNT(*) AS total FROM todos");
$result2 = $conn->query("SELECT COUNT(*) AS completed FROM todos WHERE status='completed'");
$result3 = $conn->query("SELECT COUNT(*) AS pending FROM todos WHERE status='pending'");

echo json_encode([
    "total" => $result1->fetch_assoc()['total'],
    "completed" => $result2->fetch_assoc()['completed'],
    "pending" => $result3->fetch_assoc()['pending']
]);
?>