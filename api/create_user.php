<?php
include '../auth/auth.php';
include '../config/db.php';

header('Content-Type: application/json');

if (!isAdmin()) {
    echo json_encode(["status"=>"error","message"=>"Access denied"]);
    exit();
}
?>