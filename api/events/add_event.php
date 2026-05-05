<?php
include '../../config/db.php';

$title = $_POST['title'];
$start = $_POST['start'];
$end = $_POST['end'];

$end = date('Y-m-d', strtotime($end . ' +1 day'));

mysqli_query($conn, "INSERT INTO events (title, start, end) 
                     VALUES ('$title', '$start', '$end')");
?>