<?php
include '../auth/auth.php';
include '../config/db.php';

header('Content-Type: application/json');

if ($_SESSION['role'] == 'admin') {
    $sql = "SELECT t.*, u.name AS user_name 
            FROM todos t 
            LEFT JOIN users u ON t.user_id = u.id";
} else {
    $uid = $_SESSION['user_id'];
    $sql = "SELECT * FROM todos WHERE user_id = $uid";
}

$result = $conn->query($sql);

$todos = [];

while ($row = $result->fetch_assoc()) {
    $todos[] = $row;
}

echo json_encode($todos);
?>