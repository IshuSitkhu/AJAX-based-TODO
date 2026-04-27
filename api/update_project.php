<?php
include '../config/db.php';
include '../auth/auth.php';

header('Content-Type: application/json');

if ($_SESSION['role'] != 'admin') {
    echo json_encode(["status"=>"error","message"=>"Access denied"]);
    exit();
}

$id = $_POST['id'] ?? null;
$title = $_POST['title'] ?? '';
$description = $_POST['description'] ?? '';
$users = $_POST['users'] ?? [];

if (!$id) {
    echo json_encode(["status"=>"error","message"=>"Missing ID"]);
    exit();
}

if (!is_array($users)) {
    $users = [$users];
}

$stmt = $conn->prepare("UPDATE projects SET title=?, description=? WHERE id=?");
$stmt->bind_param("ssi", $title, $description, $id);

if ($stmt->execute()) {

    // delete old relations
    $stmtDel = $conn->prepare("DELETE FROM project_users WHERE project_id=?");
    $stmtDel->bind_param("i", $id);
    $stmtDel->execute();

    // insert new users
    foreach ($users as $user_id) {
        if (!$user_id) continue;

        $stmt2 = $conn->prepare("INSERT INTO project_users (project_id, user_id) VALUES (?, ?)");
        $stmt2->bind_param("ii", $id, $user_id);
        $stmt2->execute();
    }

    echo json_encode(["status"=>"success","message"=>"Project updated"]);

} else {
    echo json_encode(["status"=>"error","message"=>"Update failed"]);
}