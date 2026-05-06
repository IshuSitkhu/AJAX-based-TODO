<?php
include '../../config/db.php';

header('Content-Type: application/json');

$event_id = $_GET['event_id'];

$result = mysqli_query($conn, "
    SELECT user_id FROM event_users WHERE event_id=$event_id
");

$users = [];

while ($row = mysqli_fetch_assoc($result)) {
    $users[] = (string)$row['user_id']; // ✅ IMPORTANT (string)
}

echo json_encode($users);