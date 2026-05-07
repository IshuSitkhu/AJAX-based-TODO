<?php
include '../../config/db.php';
session_start();

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

if ($role == 'admin') {
    $query = "SELECT * FROM events";
} else {
    $query = "
        SELECT DISTINCT e.*
        FROM events e
        JOIN event_users eu ON e.id = eu.event_id
        WHERE eu.user_id = '$user_id'
    ";
}

$result = mysqli_query($conn, $query);

$events = [];

while ($row = mysqli_fetch_assoc($result)) {

    $end = $row['end'];

    if (!empty($end)) {
        $end = date('Y-m-d', strtotime($end . ' +1 day'));
    }

    $events[] = [
        "id" => $row['id'],
        "title" => $row['title'],
        "start" => $row['start'],
        "end" => $end
    ];
}

echo json_encode($events);
?>