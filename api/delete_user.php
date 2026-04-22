<?php
include '../auth/auth.php';
include '../config/db.php';

if ($_SESSION['role'] != 'admin') {
    echo json_encode(["status"=>"error"]);
    exit();
}

$id = $_POST['id'];

$stmt = $conn->prepare("DELETE FROM users WHERE id=?");
$stmt->bind_param("i", $id);

echo json_encode([
    "status" => $stmt->execute() ? "success" : "error"
]);
?>