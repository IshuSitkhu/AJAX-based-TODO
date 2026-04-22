<?php
include '../auth/auth.php';
include '../config/db.php';

header('Content-Type: application/json');

$task = $_POST['task'];

if ($_SESSION['role'] == 'admin') {
    $user_id = $_POST['user_id'];
} else {
    $user_id = $_SESSION['user_id'];
}

$stmt = $conn->prepare("INSERT INTO todos (task, user_id, status) VALUES (?, ?, 'pending')");
$stmt->bind_param("si", $task, $user_id);

if ($stmt->execute()) {
    echo json_encode(["status"=>"success"]);
} else {
    echo json_encode(["status"=>"error"]);
}
?>