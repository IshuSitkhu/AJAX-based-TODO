<?php
include '../../config/db.php';

$id = $_POST['id'];

mysqli_query($conn, "DELETE FROM events WHERE id=$id");