<?php
include 'db.php';
header('Content-Type: application/json');

$id = $_POST['id'];

if ($conn->query("DELETE FROM todos WHERE id=$id")) {
    echo json_encode([
        "status" => "success",
        "message" => "Deleted"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Delete failed"
    ]);
}
?>