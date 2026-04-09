<?php
include 'db.php';
header('Content-Type: application/json');

$task = $_POST['task'];

if ($conn->query("INSERT INTO todos (task) VALUES ('$task')")) {
    echo json_encode([
        "status" => "success",
        "message" => "Task added"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Insert failed"
    ]);
}
?>