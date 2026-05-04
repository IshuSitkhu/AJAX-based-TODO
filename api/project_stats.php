<?php
include '../config/db.php';

header('Content-Type: application/json');

$query = "
SELECT p.title AS project, COUNT(t.id) AS total_tasks
FROM projects p
LEFT JOIN project_tasks t ON p.id = t.project_id
GROUP BY p.id
";

$result = mysqli_query($conn, $query);

if (!$result) {
    echo json_encode(["error" => mysqli_error($conn)]);
    exit;
}

$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode($data);