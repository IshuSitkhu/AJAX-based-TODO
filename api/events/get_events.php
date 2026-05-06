<?php
include '../../config/db.php';

header('Content-Type: application/json');

$result = mysqli_query($conn, "SELECT * FROM events");

$events = [];

while ($row = mysqli_fetch_assoc($result)) {

    $start = $row['start'];
    $end = $row['end'];

    if (!empty($end)) {
        $end = date('Y-m-d', strtotime($end . ' +1 day'));
    }

    $events[] = [
        "id" => $row['id'],
        "title" => $row['title'],
        "start" => $start,
        "end" => $end
    ];
}

echo json_encode($events);
?>