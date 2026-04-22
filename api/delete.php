<?php
include '../auth/auth.php';
include '../config/db.php';

header('Content-Type: application/json');

$id = $_POST['id'];

// STAFF can delete only their own tasks
if ($_SESSION['role'] == 'staff') {
    $stmt = $conn->prepare("DELETE FROM todos WHERE id=? AND user_id=?");
    $stmt->bind_param("ii", $id, $_SESSION['user_id']);
} else {
    // ADMIN can delete any task
    $stmt = $conn->prepare("DELETE FROM todos WHERE id=?");
    $stmt->bind_param("i", $id);
}

if ($stmt->execute()) {
    echo json_encode(["status"=>"success"]);
} else {
    echo json_encode(["status"=>"error"]);
}
?>