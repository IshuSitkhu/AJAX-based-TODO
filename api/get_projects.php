<?php
include '../config/db.php';
include '../auth/auth.php';

header('Content-Type: application/json');

// only admin
if ($_SESSION['role'] != 'admin') {
    echo json_encode([]);
    exit();
}

$sql = "SELECT * FROM projects ORDER BY id DESC";
$result = $conn->query($sql);

$projects = [];

while ($row = $result->fetch_assoc()) {
    $projects[] = $row;
}

echo json_encode($projects);
?>