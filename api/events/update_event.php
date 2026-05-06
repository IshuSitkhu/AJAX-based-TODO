<?php
include '../../config/db.php';

$id = $_POST['id'];
$title = $_POST['title'];
$start = $_POST['start'];
$end = $_POST['end'];
$users = $_POST['users'];

mysqli_query($conn, "UPDATE events 
SET title='$title', start='$start', end='$end'
WHERE id=$id");

// delete old users
mysqli_query($conn, "DELETE FROM event_users WHERE event_id=$id");

// insert new users
if (!empty($users)) {
    foreach ($users as $uid) {
        mysqli_query($conn, "INSERT INTO event_users (event_id, user_id)
        VALUES ($id, $uid)");
    }
}