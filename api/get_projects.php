<?php
include '../config/db.php';
include '../auth/auth.php';

header('Content-Type: application/json');

$sql = "SELECT * FROM projects ORDER BY id DESC";
$result = $conn->query($sql);

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);