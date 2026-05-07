<?php
include '../../config/db.php';

$event_id = $_POST['event_id'];
$user_id = $_POST['user_id'];

mysqli_query($conn, "
    DELETE FROM event_users 
    WHERE event_id=$event_id AND user_id=$user_id
");

echo json_encode(["success" => true]);
?>