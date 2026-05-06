<?php
include '../../config/db.php';

$title = $_POST['title'];
$start = $_POST['start'];
$end = $_POST['end'];
$users = $_POST['users'];

mysqli_query($conn, "INSERT INTO events (title, start, end)
VALUES ('$title', '$start', '$end')");

$event_id = mysqli_insert_id($conn);

// insert users
if (!empty($users)) {
    foreach ($users as $uid) {
        mysqli_query($conn, "INSERT INTO event_users (event_id, user_id)
        VALUES ($event_id, $uid)");
    }
}