<?php
include '../auth/auth.php';

if (!isAdmin()) {
    die("Access Denied");
}
?>