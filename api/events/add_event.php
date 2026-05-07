<?php
include '../../config/db.php';
session_start();

$title = $_POST['title'];
$start = $_POST['start'];
$end = $_POST['end'];
$users = $_POST['users'] ?? [];

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// EVENT TYPE LOGIC
if ($role == 'admin') {
    $event_type = 'admin';
} else {
    $event_type = 'staff';
}

if (!is_array($users)) {
    $users = [$users];
}

// INSERT EVENT
mysqli_query($conn, "
    INSERT INTO events (title, start, end, created_by, event_type)
    VALUES ('$title', '$start', '$end', '$user_id', '$event_type')
");

$event_id = mysqli_insert_id($conn);

// ASSIGN USERS
foreach ($users as $uid) {

    $uid = intval($uid);

    if ($uid > 0) {
        mysqli_query($conn, "
            INSERT INTO event_users (event_id, user_id)
            VALUES ($event_id, $uid)
        ");
    }
}

echo json_encode(["success" => true]);
?>