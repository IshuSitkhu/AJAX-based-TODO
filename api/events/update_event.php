<?php
include '../../config/db.php';

$id = $_POST['id'];
$title = $_POST['title'];
$start = $_POST['start'];
$end = $_POST['end'];

mysqli_query($conn, "UPDATE events 
SET title='$title',
    start='$start',
    end='$end'
WHERE id=$id");
?>