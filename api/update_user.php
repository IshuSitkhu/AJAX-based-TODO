<?php
include '../auth/auth.php';
include '../config/db.php';

header('Content-Type: application/json');

if ($_SESSION['role'] != 'admin') {
    echo json_encode(["status"=>"error","message"=>"Access denied"]);
    exit();
}

// GET DATA
$id = $_POST['id'] ?? '';
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// VALIDATION

// required fields
if (!$id || !$name || !$email) {
    echo json_encode(["status"=>"error","message"=>"Required fields missing"]);
    exit();
}

// name validation
if (strlen($name) < 3) {
    echo json_encode(["status"=>"error","message"=>"Name must be at least 3 characters"]);
    exit();
}

// email validation (ONLY GMAIL)
if (!preg_match("/^[a-zA-Z0-9._%+-]+@gmail\.com$/", $email)) {
    echo json_encode(["status"=>"error","message"=>"Only Gmail addresses allowed"]);
    exit();
}

// DUPLICATE EMAIL CHECK
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
$stmt->bind_param("si", $email, $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(["status"=>"error","message"=>"Email already exists"]);
    exit();
}

// UPDATE WITH / WITHOUT PASSWORD
if ($password != "") {

    // validate password
    if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#]).{8,}$/", $password)) {
        echo json_encode(["status"=>"error","message"=>"Weak password"]);
        exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("UPDATE users SET name=?, email=?, password=? WHERE id=?");
    $stmt->bind_param("sssi", $name, $email, $hashedPassword, $id);

} else {

    $stmt = $conn->prepare("UPDATE users SET name=?, email=? WHERE id=?");
    $stmt->bind_param("ssi", $name, $email, $id);
}

// EXECUTE
if ($stmt->execute()) {

    // update session if current user edits self
    if ($_SESSION['user_id'] == $id) {
        $_SESSION['name'] = $name;
    }

    echo json_encode(["status"=>"success","message"=>"User updated successfully"]);

} else {
    echo json_encode(["status"=>"error","message"=>"Update failed"]);
}
?>