<?php
include '../auth/auth.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

<h2>Welcome, <?php echo $_SESSION['name']; ?></h2>

<!-- LOGOUT BUTTON -->
<button onclick="logout()">Logout</button>

<hr>

<?php if ($_SESSION['role'] == 'admin') { ?>

    <h3>Admin Panel</h3>

    <a href="create_user.php">Create User</a><br>
    <a href="users.php">View Users</a><br>
    <a href="todo.php">Manage Tasks</a><br>

<?php } else { ?>

    <h3>Staff Panel</h3>

    <a href="todo.php">My Tasks</a><br>

<?php } ?>

<script>
function logout() {
    $.ajax({
        url: "../api/logout.php",
        method: "GET",
        success: function () {
            window.location.href = "login.php";
        }
    });
}
</script>

</body>
</html>