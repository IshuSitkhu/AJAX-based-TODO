<?php
include '../config/db.php';
include '../auth/auth.php';

header('Content-Type: application/json');

if ($_SESSION['role'] != 'admin') {
    echo json_encode(["status" => "error"]);
    exit();
}

$id = $_POST['id'];
$title = $_POST['title'];
$description = $_POST['description'];
$users = $_POST['users'];

// 1. update project
$stmt = $conn->prepare("UPDATE projects SET title=?, description=? WHERE id=?");
$stmt->bind_param("ssi", $title, $description, $id);

if ($stmt->execute()) {

    // 2. delete old user relations
    $conn->query("DELETE FROM project_users WHERE project_id = $id");

    // 3. insert new users
    if (!empty($users)) {
        foreach ($users as $user_id) {

            $stmt2 = $conn->prepare("INSERT INTO project_users (project_id, user_id) VALUES (?, ?)");
            $stmt2->bind_param("ii", $id, $user_id);
            $stmt2->execute();
        }
    }

    echo json_encode([
        "status" => "success",
        "message" => "Project updated"
    ]);

} else {
    echo json_encode([
        "status" => "error",
        "message" => "Update failed"
    ]);
}
?>