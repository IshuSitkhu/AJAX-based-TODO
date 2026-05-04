<?php
include '../../config/db.php';

$title = $_POST['title'];
$start = $_POST['start'];

mysqli_query($conn, "INSERT INTO events (title, start) VALUES ('$title', '$start')");