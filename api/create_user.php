<?php
include '../auth/auth.php';
include '../config/db.php';

header('Content-Type: application/json');

if (!isAdmin()) {
    echo json_encode(["status" => "error", "message" => "Access denied"]);
    exit();
}

// GET DATA
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// =========================
// VALIDATION
// =========================

// empty check
if (!$name || !$email || !$password) {
    echo json_encode(["status" => "error", "message" => "All fields required"]);
    exit();
}

// name validation
if (strlen($name) < 3) {
    echo json_encode(["status" => "error", "message" => "Name must be at least 3 characters"]);
    exit();
}

// email validation (ONLY GMAIL)
if (!preg_match("/^[a-zA-Z0-9._%+-]+@gmail\.com$/", $email)) {
    echo json_encode(["status" => "error", "message" => "Only Gmail addresses allowed"]);
    exit();
}

// strong password (allows # also)
if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#]).{8,}$/", $password)) {
    echo json_encode([
        "status" => "error",
        "message" => "Password must contain uppercase, lowercase, number, special character"
    ]);
    exit();
}

// =========================
// DUPLICATE EMAIL CHECK
// =========================
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Email already exists"]);
    exit();
}

// INSERT USER
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

$stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'staff')");
$stmt->bind_param("sss", $name, $email, $hashedPassword);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "User created successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to create user"]);
}
?>