<?php
include '../auth/auth.php';

if ($_SESSION['role'] != 'staff') {
    die("Access Denied");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Staff Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="../assets/js/staff.js"></script>
</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-primary">
    <div class="container-fluid">
        <span class="navbar-brand">Staff Dashboard</span>
        <div class="text-white">
            Welcome, <?php echo $_SESSION['name']; ?>
            <button onclick="logout()" class="btn btn-light btn-sm ms-3">Logout</button>
        </div>
    </div>
</nav>

<div class="container mt-4">

    <div class="card shadow-sm p-3">
        <h4 class="mb-3">My Project Tasks</h4>

        <ul id="taskList" class="list-group"></ul>
    </div>

</div>

<script>
// =========================
// LOAD PROJECT TASKS
// =========================
function loadMyTasks() {

    $.get("../api/staff_project_tasks.php", function(data) {

        let html = "";

        if (data.length === 0) {
            html = `<li class="list-group-item text-muted">No tasks assigned</li>`;
        } else {

            data.forEach(t => {

    html += `
    <li class="list-group-item d-flex justify-content-between align-items-center">

        <div>
            <strong>${t.task}</strong><br>
            <small class="text-muted">
                Project: ${t.project_name}
            </small>
        </div>

        <div>
            <span class="badge bg-${t.status === 'completed' ? 'success' : 'warning'}">
                ${t.status}
            </span>

            ${t.status !== 'completed' ? `
                <button class="btn btn-sm btn-success ms-2"
                    onclick="markCompleted(${t.id})">
                    Done
                </button>
            ` : ''}
        </div>

    </li>`;
});
        }

        $("#taskList").html(html);

    }, "json");
}

// load on page start
loadMyTasks();

// =========================
// LOGOUT
// =========================
function logout() {
    $.get("../api/logout.php", function() {
        window.location.href = "login.php";
    });
}
</script>

</body>
</html>