<?php
include '../../config/db.php';

header('Content-Type: application/json');

$result = mysqli_query($conn, "SELECT * FROM events");

$events = [];

while ($row = mysqli_fetch_assoc($result)) {
    $events[] = [
    "id" => $row['id'],
    "title" => $row['title'],
    "start" => $row['start'],
    "end" => $row['end']
];
}

echo json_encode($events);