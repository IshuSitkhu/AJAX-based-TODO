<?php
include '../auth/auth.php';
include '../config/db.php';

header('Content-Type: application/json');

if (!isAdmin()) {
    echo json_encode(["status" => "error", "message" => "Access denied"]);
    exit();
}

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (!$name || !$email || !$password) {
    echo json_encode(["status" => "error", "message" => "All fields required"]);
    exit();
}

// check duplicate email
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Email already exists"]);
    exit();
}

// hash password
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// insert user
$stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'staff')");
$stmt->bind_param("sss", $name, $email, $hashedPassword);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "User created"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to create user"]);
}
?>