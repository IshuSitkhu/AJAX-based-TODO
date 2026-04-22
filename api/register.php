<?php
include '../config/db.php';
header('Content-Type: application/json');

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];

// validation
if (strlen($name) < 3) {
    echo json_encode(["status"=>"error","message"=>"Name too short"]);
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["status"=>"error","message"=>"Invalid email"]);
    exit();
}

// check duplicate email
$stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(["status"=>"error","message"=>"Email already exists"]);
    exit();
}

// hash password
$hashed = password_hash($password, PASSWORD_DEFAULT);

// DEFAULT ROLE = staff
$role = "staff";

$stmt = $conn->prepare("INSERT INTO users (name,email,password,role) VALUES (?,?,?,?)");
$stmt->bind_param("ssss", $name, $email, $hashed, $role);

if ($stmt->execute()) {
    echo json_encode(["status"=>"success"]);
} else {
    echo json_encode(["status"=>"error","message"=>"Registration failed"]);
}
?>