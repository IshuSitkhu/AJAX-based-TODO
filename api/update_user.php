<?php
include '../auth/auth.php';
include '../config/db.php';

header('Content-Type: application/json');

if ($_SESSION['role'] != 'admin') {
    echo json_encode(["status"=>"error","message"=>"Access denied"]);
    exit();
}

$id = $_POST['id'];
$name = $_POST['name'];
$email = $_POST['email'];

$stmt = $conn->prepare("UPDATE users SET name=?, email=? WHERE id=?");
$stmt->bind_param("ssi", $name, $email, $id);

if ($stmt->execute()) {
    if ($_SESSION['user_id'] == $id) {
        $_SESSION['name'] = $name;
    }
    echo json_encode(["status"=>"success"]);
} else {
    echo json_encode(["status"=>"error"]);
}
?>
