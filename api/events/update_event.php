<?php
include '../../config/db.php';
session_start();

header('Content-Type: application/json');

$id = $_POST['id'];
$title = $_POST['title'];
$start = $_POST['start'];
$end = $_POST['end'];
$users = $_POST['users'] ?? [];

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

$event = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT * FROM events WHERE id=$id
"));

if (!$event) {
    echo json_encode([
        "success" => false,
        "message" => "Event not found"
    ]);
    exit;
}


//  STAFF cannot edit admin events
if ($role != 'admin') {

    if ($event['event_type'] === 'admin') {
        echo json_encode([
            "success" => false,
            "message" => "No permission (admin event)"
        ]);
        exit;
    }

    //  STAFF can only edit their own events
    if ($event['created_by'] != $user_id) {
        echo json_encode([
            "success" => false,
            "message" => "No permission (not owner)"
        ]);
        exit;
    }
}

if ($event['event_type'] === 'staff') {
    $users = [];
}

mysqli_query($conn, "
    UPDATE events
    SET title = '$title',
        start = '$start',
        end = '$end'
    WHERE id = $id
");

if ($role === 'admin' && $event['event_type'] === 'admin') {

    if (!is_array($users)) {
        $users = [$users];
    }

    foreach ($users as $uid) {

        $uid = intval($uid);

        if ($uid > 0) {

            mysqli_query($conn, "
                INSERT INTO event_users (event_id, user_id)
                SELECT $id, $uid
                WHERE NOT EXISTS (
                    SELECT 1 FROM event_users
                    WHERE event_id = $id AND user_id = $uid
                )
            ");
        }
    }
}

echo json_encode([
    "success" => true
]);
?>