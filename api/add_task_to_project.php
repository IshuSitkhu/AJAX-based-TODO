<?php
include '../config/db.php';
include '../auth/auth.php';
include '../config/mail_config.php';

header('Content-Type: application/json');

// PHPMailer (MANUAL SETUP)
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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

// GET USER DETAILS
$userStmt = $conn->prepare("SELECT email, name FROM users WHERE id=?");
$userStmt->bind_param("i", $assigned_user_id);
$userStmt->execute();
$userResult = $userStmt->get_result()->fetch_assoc();

if (!$userResult) {
    echo json_encode([
        "status" => "error",
        "message" => "User not found"
    ]);
    exit();
}

$userEmail = $userResult['email'];
$userName  = $userResult['name'];

// GET PROJECT NAME
$projectStmt = $conn->prepare("SELECT title FROM projects WHERE id=?");
$projectStmt->bind_param("i", $project_id);
$projectStmt->execute();
$project = $projectStmt->get_result()->fetch_assoc();

$projectName = $project['title'] ?? 'Unknown Project';

// GET ADMIN NAME
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

// EXECUTE + EMAIL


if ($stmt->execute()) {

    // RETURN RESPONSE FIRST
    echo json_encode([
        "status" => "success",
        "message" => "Task assigned successfully"
    ]);

    // FORCE RESPONSE TO FRONTEND
    ignore_user_abort(true);
    ob_flush();
    flush();

    // NOW SEND EMAIL (AFTER RESPONSE)

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;

        $mail->Username = MAIL_USER;
        $mail->Password = MAIL_PASS;

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom(MAIL_USER, 'Task Manager');
        $mail->addAddress($userEmail, $userName);

        $mail->isHTML(true);
        $mail->Subject = "New Task Assigned - $projectName";

        $mail->Body = "
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

                 <p style='color:gray'>
                    Please login to your dashboard to update the task status.
                </p>
                

            </div>
        ";

        $mail->send();

    } catch (Exception $e) {
        error_log("Mail Error: " . $mail->ErrorInfo);
    }

} else {
    echo json_encode([
        "status" => "error",
        "message" => $stmt->error
    ]);
}
?>