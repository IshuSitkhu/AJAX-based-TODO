<?php
include 'auth.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>

<body>

<h2>Welcome <?php echo $_SESSION['name']; ?></h2>

<?php if ($_SESSION['role'] == 'admin'): ?>
    <h3>Admin Panel</h3>
    <a href="create_user.html">Create User</a>
    <a href="users.html">View Users</a>
<?php else: ?>
    <h3>Staff Panel</h3>
<?php endif; ?>

<br><br>
<a href="logout.php">Logout</a>

<hr>

<!-- Include your existing todo UI -->
<iframe src="index.html" width="500" height="500"></iframe>

</body>
</html>