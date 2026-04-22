<?php
session_start();
include '../config/db.php';
header('Content-Type: application/json');

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo json_encode(["status"=>"error","message"=>"User not found"]);
    exit();
}

$user = $result->fetch_assoc();

if (!password_verify($password, $user['password'])) {
    echo json_encode(["status"=>"error","message"=>"Invalid password"]);
    exit();
}

// SESSION
$_SESSION['user_id'] = $user['id'];
$_SESSION['name'] = $user['name'];
$_SESSION['role'] = $user['role'];

//  ROLE BASED REDIRECT ADDED
$redirect = ($user['role'] == 'admin')
    ? "dashboard.php"
    : "staff_dashboard.php";

echo json_encode([
    "status" => "success",
    "message" => "Login successful",
    "redirect" => $redirect
]);
?>