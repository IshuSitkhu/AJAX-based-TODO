<?php
include '../auth/auth.php';

if ($_SESSION['role'] != 'admin') {
    die("Access Denied");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

<h2>Welcome Admin: <?php echo $_SESSION['name']; ?></h2>

<!-- LOGOUT -->
<button onclick="logout()">Logout</button>

<hr>

<!-- TASK STATS -->
<h3>Task Overview</h3>
<div id="stats">Loading stats...</div>

<hr>

<h3>Admin Panel</h3>

<button onclick="openCreate()">Create User</button>
<button onclick="openUsers()">View Users</button>
<button onclick="openTasks()">Manage Tasks</button>

<hr>

<!-- CREATE USER -->
<div id="create" class="section" style="display:none;">
    <h3>Create User</h3>

    <input type="hidden" id="editId">

    <input type="text" id="name" placeholder="Name"><br><br>
    <input type="email" id="email" placeholder="Email"><br><br>
    <input type="password" id="password" placeholder="Password"><br><br>

    <button onclick="createUser()">Submit</button>
</div>

<!-- USERS -->
<div id="users" class="section" style="display:none;">
    <h3>Users List</h3>
    <ul id="userList"></ul>
</div>

<!-- TASKS -->
<div id="tasks" class="section" style="display:none;">
    <h3>All Tasks</h3>
    <ul id="taskList"></ul>
</div>

<script>
// logout
function logout() {
    $.get("../api/logout.php", function () {
        window.location.href = "login.php";
    });
}
</script>

<script src="../assets/js/admin.js"></script>

</body>
</html>