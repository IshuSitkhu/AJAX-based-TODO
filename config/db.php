<?php
$conn = new mysqli("localhost", "root", "", "todo_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>