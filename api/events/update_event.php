<?php
include '../../config/db.php';

$id = $_POST['id'];
$title = $_POST['title'];

mysqli_query($conn, "UPDATE events SET title='$title' WHERE id=$id");