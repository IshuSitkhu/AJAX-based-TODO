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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery + SweetAlert -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="navbar-brand">Admin Dashboard</span>
        <div class="text-white">
            Welcome, <?php echo $_SESSION['name']; ?>
            <button onclick="logout()" class="btn btn-danger btn-sm ms-3">Logout</button>
        </div>
    </div>
</nav>

<div class="container mt-4">

    <!-- <div class="card shadow-sm p-3 mb-4">
        <h4 class="mb-3">Task Overview</h4>
        <div id="stats">Loading stats...</div>
    </div> -->

    <div class="mb-4">
        <button onclick="openCreate()" class="btn btn-primary me-2">Create User</button>
        <button onclick="openUsers()" class="btn btn-info me-2 text-white">View Users</button>
        <button onclick="openTasks()" class="btn btn-success">Manage Tasks</button>
        <button onclick="openProjects()" class="btn btn-warning text-white">Projects </button>
    </div>

    <div id="create" class="section card p-3 shadow-sm mb-4" style="display:none;">
        <h4>Create User</h4>

        <input type="hidden" id="editId">

        <input type="text" id="name" class="form-control mb-2" placeholder="Name">
        <input type="email" id="email" class="form-control mb-2" placeholder="Email">
        <input type="password" id="password" class="form-control mb-3" placeholder="Password">

        <button type="button" onclick="createUser()" class="btn btn-primary">Submit</button>
    </div>

    <div id="users" class="section card p-3 shadow-sm mb-4" style="display:none;">
        <h4>Users List</h4>
        <ul id="userList" class="list-group"></ul>
    </div>

    <div id="tasks" class="section card p-3 shadow-sm mb-4" style="display:none;">
        <h4>Manage Tasks</h4>

        <!-- CREATE TASK -->
        <input type="text" id="taskText" class="form-control mb-2" placeholder="Task">

        <select id="assignUser" class="form-select mb-2"></select>

        <button onclick="createTask()" class="btn btn-success mb-3">Add Task</button>

        <hr>

        <ul id="taskList" class="list-group"></ul>
    </div>

<div id="projects" class="section card p-3 shadow-sm mb-4" style="display:none;">

    <h4>Projects</h4>

    <!-- CREATE / EDIT FORM -->
    <div id="projectForm">

        <input type="text" id="projectTitle" class="form-control mb-2" placeholder="Project Title">

        <textarea id="projectDesc" class="form-control mb-2" placeholder="Description"></textarea>

        
        <button id="projectBtn" onclick="createProject()" class="btn btn-warning text-white mb-3">
            Save Project
        </button>

        <hr>

        <h4>Project Lists </h4>
    </div>

    <ul id="projectList" class="list-group"></ul>

    <div id="projectView" class="mt-3" style="display:none;"></div>

</div>

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

<script>
$(document).ready(function () {
    openCreate(); // show Create User by default
});
</script>
</body>
</html>