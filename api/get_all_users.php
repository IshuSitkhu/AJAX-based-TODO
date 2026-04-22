<?php
include '../auth/auth.php';
include '../config/db.php';

header('Content-Type: application/json');

if ($_SESSION['role'] != 'admin') {
    echo json_encode([]);
    exit();
}

$result = $conn->query("SELECT id, name, email, role FROM users");

$users = [];

while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

echo json_encode($users);
?>