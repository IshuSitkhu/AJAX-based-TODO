<?php
include '../auth/auth.php';
include '../config/db.php';

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM todos WHERE user_id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

$tasks = [];

while ($row = $result->fetch_assoc()) {
    $tasks[] = $row;
}

echo json_encode($tasks);
?>