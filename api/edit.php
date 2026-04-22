<?php
include '../auth/auth.php';
include '../config/db.php';

header('Content-Type: application/json');

$id = $_POST['id'];
$task = $_POST['task'];

if ($_SESSION['role'] == 'staff') {
    $stmt = $conn->prepare("UPDATE todos SET task=? WHERE id=? AND user_id=?");
    $stmt->bind_param("sii", $task, $id, $_SESSION['user_id']);
} else {
    $stmt = $conn->prepare("UPDATE todos SET task=? WHERE id=?");
    $stmt->bind_param("si", $task, $id);
}

if ($stmt->execute()) {
    echo json_encode(["status"=>"success"]);
} else {
    echo json_encode(["status"=>"error"]);
}
?>