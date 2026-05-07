<?php
include '../../config/db.php';

$id = $_POST['id'];
$title = $_POST['title'];
$start = $_POST['start'];
$end = $_POST['end'];

mysqli_query($conn, "
    UPDATE events
    SET title='$title', start='$start', end='$end'
    WHERE id=$id
");

if (isset($_POST['users'])) {

    $users = $_POST['users'];

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
                    WHERE event_id=$id AND user_id=$uid
                )
            ");
        }
    }
}

echo json_encode(["success" => true]);
?>