<?php
include 'auth.php';
include 'db.php';
header('Content-Type: application/json');

if ($_SESSION['role'] != 'admin') {
    echo json_encode(["status"=>"error","message"=>"Access denied"]);
    exit();
}

$id = $_POST['id'];

// Prevent deleting yourself 
if ($id == $_SESSION['user_id']) {
    echo json_encode(["status"=>"error","message"=>"You cannot delete yourself"]);
    exit();
}

$stmt = $conn->prepare("DELETE FROM users WHERE id=?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(["status"=>"success"]);
} else {
    echo json_encode(["status"=>"error","message"=>"Delete failed"]);
}
?>