<?php
session_start();

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// AUTH CHECK
if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.php");
    exit();
}

// ROLE FUNCTIONS
function isAdmin() {
    return $_SESSION['role'] === 'admin';
}

function isStaff() {
    return $_SESSION['role'] === 'staff';
}
?>