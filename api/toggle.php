<?php
include '../auth/auth.php';
include '../config/db.php';

header('Content-Type: application/json');

$id = $_POST['id'];

// get current status
$result = $conn->query("SELECT status FROM todos WHERE id=$id");
$row = $result->fetch_assoc();

$newStatus = ($row['status'] == 'completed') ? 'pending' : 'completed';

if ($_SESSION['role'] == 'staff') {
    $stmt = $conn->prepare("UPDATE todos SET status=? WHERE id=? AND user_id=?");
    $stmt->bind_param("sii", $newStatus, $id, $_SESSION['user_id']);
} else {
    $stmt = $conn->prepare("UPDATE todos SET status=? WHERE id=?");
    $stmt->bind_param("si", $newStatus, $id);
}

if ($stmt->execute()) {
    echo json_encode(["status"=>"success", "newStatus"=>$newStatus]);
} else {
    echo json_encode(["status"=>"error"]);
}
?>