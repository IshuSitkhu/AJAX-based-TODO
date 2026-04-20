<?php
include 'auth.php';
include 'db.php';
header('Content-Type: application/json');

if ($_SESSION['role'] != 'admin') {
    echo json_encode(["status"=>"error","message"=>"Access denied"]);
    exit();
}

$name = trim($_POST['name']);
$email = trim($_POST['email']);
$password = $_POST['password'];
$role = $_POST['role'];

// Name validation
if (strlen($name) < 3) {
    echo json_encode(["status"=>"error","message"=>"Name must be at least 3 characters"]);
    exit();
}

// Email validation
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["status"=>"error","message"=>"Invalid email format"]);
    exit();
}

// Strong password validation
$pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/";

if (!preg_match($pattern, $password)) {
    echo json_encode([
        "status" => "error",
        "message" => "Password must be at least 8 characters and include uppercase, lowercase, number, and special character"
    ]);
    exit();
}

// Prevent XSS (basic)
$name = htmlspecialchars($name);
$email = htmlspecialchars($email);

// Check duplicate email
$stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(["status"=>"error","message"=>"Email already exists"]);
    exit();
}

// Hash password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert user (prepared statement)
$stmt = $conn->prepare("INSERT INTO users (name,email,password,role) VALUES (?,?,?,?)");
$stmt->bind_param("ssss", $name, $email, $hashedPassword, $role);

if ($stmt->execute()) {
    echo json_encode(["status"=>"success","message"=>"User created"]);
} else {
    echo json_encode(["status"=>"error","message"=>"Insert failed"]);
}
?>