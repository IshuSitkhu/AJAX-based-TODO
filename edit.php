<?php
include 'db.php';
header('Content-Type: application/json');

$id = $_POST['id'];
$task = $_POST['task'];

if ($conn->query("UPDATE todos SET task='$task' WHERE id=$id")) {
    echo json_encode([
        "status" => "success",
        "message" => "Updated successfully"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Update failed"
    ]);
}
?>