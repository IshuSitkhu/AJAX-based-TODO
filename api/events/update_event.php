<?php
include '../../config/db.php';

$id = $_POST['id'];
$title = $_POST['title'];
$start = $_POST['start'];
$end = $_POST['end'];

// convert +1 day fix
$end = date('Y-m-d', strtotime($end . ' +1 day'));

mysqli_query($conn, "UPDATE events 
    SET title='$title',
        start='$start',
        end='$end'
    WHERE id=$id
");
?>