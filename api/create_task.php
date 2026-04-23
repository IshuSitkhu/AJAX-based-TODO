<?php
include '../auth/auth.php';
include '../config/db.php';

header('Content-Type: application/json');

// allow both admin and staff
if (!isset($_SESSION['role'])) {
    echo json_encode(["status"=>"error"]);
    exit();
}

$task = $_POST['task'];
$user_id = $_SESSION['user_id']; // default = self
$assigned_by = $_SESSION['user_id'];

// admin can assign to others
if ($_SESSION['role'] == 'admin' && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
}

$stmt = $conn->prepare("INSERT INTO todos (task, user_id, assigned_by, status) VALUES (?, ?, ?, 'pending')");
$stmt->bind_param("sii", $task, $user_id, $assigned_by);

if ($stmt->execute()) {
    echo json_encode(["status"=>"success"]);
} else {
    echo json_encode(["status"=>"error"]);
}
?>