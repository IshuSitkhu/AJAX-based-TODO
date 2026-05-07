<?php
include '../../config/db.php';
session_start();

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

if ($role === 'admin') {

    $query = "
        SELECT id, title, start, end, created_by, event_type
        FROM events
    ";

} else {

    $query = "
        SELECT DISTINCT e.id, e.title, e.start, e.end, e.created_by, e.event_type
        FROM events e
        LEFT JOIN event_users eu ON e.id = eu.event_id
        WHERE eu.user_id = '$user_id'
           OR e.created_by = '$user_id'
    ";
}

$result = mysqli_query($conn, $query);

$events = [];

while ($row = mysqli_fetch_assoc($result)) {

    $start = $row['start'];
    $end   = $row['end'];

    if (!empty($end)) {
        $end = date('Y-m-d', strtotime($end . ' +1 day'));
    } else {
        $end = null;
    }

    $events[] = [
        "id" => (int)$row['id'],
        "title" => $row['title'],
        "start" => $start,
        "end"   => $end,

        "extendedProps" => [
            "event_type" => $row['event_type'],
            "created_by" => (int)$row['created_by']
        ]
    ];
}

echo json_encode($events);
?>