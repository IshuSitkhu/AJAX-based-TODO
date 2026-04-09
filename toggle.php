<?php
include 'db.php';
header('Content-Type: application/json');

$id = $_POST['id'];

$result = $conn->query("SELECT status FROM todos WHERE id=$id");
$row = $result->fetch_assoc();

$newStatus = ($row['status'] == 'pending') ? 'completed' : 'pending';

if ($conn->query("UPDATE todos SET status='$newStatus' WHERE id=$id")) {
    echo json_encode([
        "status" => "success",
        "newStatus" => $newStatus
    ]);
} else {
    echo json_encode([
        "status" => "error"
    ]);
}
?>