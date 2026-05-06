<?php
include '../../config/db.php';

header('Content-Type: application/json');

$result = mysqli_query($conn, "SELECT id, name FROM users");

$users = [];

while ($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
}

echo json_encode($users);