<?php
include 'db.php';

$result = $conn->query("SELECT * FROM todos");

$todos = [];

while ($row = $result->fetch_assoc()) {
    $todos[] = $row;
}

echo json_encode($todos);
?>