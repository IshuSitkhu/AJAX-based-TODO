<?php
include '../config/db.php';
include '../auth/auth.php';
include '../config/mail_config.php';

header('Content-Type: application/json');

// INPUT
$project_id = $_POST['project_id'];
$task = trim($_POST['task']);
$assigned_user_id = $_POST['assigned_user_id'];

// VALIDATION
if ($task == "" || empty($assigned_user_id)) {
    echo json_encode([
        "status" => "error",
        "message" => "Task and user are required"
    ]);
    exit();
}

// CHECK USER IN PROJECT
$check = $conn->prepare("
    SELECT id FROM project_users 
    WHERE project_id=? AND user_id=?
");
$check->bind_param("ii", $project_id, $assigned_user_id);
$check->execute();
$res = $check->get_result();

if ($res->num_rows == 0) {
    echo json_encode([
        "status" => "error",
        "message" => "User not assigned to this project"
    ]);
    exit();
}

// GET USER
$userStmt = $conn->prepare("SELECT email, name FROM users WHERE id=?");
$userStmt->bind_param("i", $assigned_user_id);
$userStmt->execute();
$userResult = $userStmt->get_result()->fetch_assoc();

$userEmail = $userResult['email'];
$userName = $userResult['name'];

// GET PROJECT
$projectStmt = $conn->prepare("SELECT title FROM projects WHERE id=?");
$projectStmt->bind_param("i", $project_id);
$projectStmt->execute();
$project = $projectStmt->get_result()->fetch_assoc();
$projectName = $project['title'] ?? 'Project';

// ADMIN
$adminStmt = $conn->prepare("SELECT name FROM users WHERE id=?");
$adminStmt->bind_param("i", $_SESSION['user_id']);
$adminStmt->execute();
$admin = $adminStmt->get_result()->fetch_assoc();
$adminName = $admin['name'] ?? 'Admin';

// INSERT TASK
$stmt = $conn->prepare("
    INSERT INTO project_tasks 
    (project_id, task, assigned_user_id, assigned_by, status)
    VALUES (?, ?, ?, ?, 'pending')
");

$stmt->bind_param("isii", $project_id, $task, $assigned_user_id, $_SESSION['user_id']);

if ($stmt->execute()) {

    // =========================
    // SAVE EMAIL TO QUEUE (FAST ⚡)
    // =========================

    $subject = "New Task Assigned - $projectName";

    $body = "
        <div style='font-family: Arial; padding:15px'>
            <h2>New Task Assigned</h2>

            <p>Hello <b>$userName</b>,</p>

            <p>You have been assigned a new task.</p>

            <hr>

            <p><b>Project:</b> $projectName</p>
            <p><b>Task:</b> $task</p>
            <p><b>Assigned By:</b> $adminName</p>
            <p><b>Status:</b> Pending</p>

            <hr>

            <p style='color:gray'>Please login to update task status.</p>
        </div>
    ";

    $queue = $conn->prepare("
        INSERT INTO email_queue (email, name, subject, body)
        VALUES (?, ?, ?, ?)
    ");

    $queue->bind_param("ssss", $userEmail, $userName, $subject, $body);
    $queue->execute();

    echo json_encode([
        "status" => "success",
        "message" => "Task assigned successfully"
    ]);

} else {
    echo json_encode([
        "status" => "error",
        "message" => $stmt->error
    ]);
}
?>