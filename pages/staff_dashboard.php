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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

    <div class="card shadow-sm p-3 mb-4">
        <h4 class="mb-3">Add Task</h4>

        <div class="d-flex gap-2">
            <input type="text" id="taskInput" class="form-control" placeholder="Enter task">
            <button onclick="addTask()" class="btn btn-success">Add</button>
        </div>
    </div>

    <div class="card shadow-sm p-3">
        <h4 class="mb-3">My Tasks</h4>

        <ul id="myTasks" class="list-group"></ul>
    </div>

</div>

<script>
function loadMyTasks() {

    $.get("../api/my_tasks.php", function(data) {

        let html = "";

        data.forEach(t => {
            html += `<li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>${t.task}</strong><br>
                    <small class="text-muted">
                        Added by ${t.admin_name ? t.admin_name : 'Admin'}
                    </small>
                </div>
                <span class="badge bg-info text-dark">${t.status}</span>
            </li>`;
        });

        $("#myTasks").html(html);

    }, "json");
}

loadMyTasks();

function logout() {
    $.get("../api/logout.php", function() {
        window.location.href = "login.php";
    });
}

function addTask() {

    let task = $("#taskInput").val().trim();

    if (task === "") {
        Swal.fire("Error", "Task cannot be empty", "error");
        return;
    }

    $.ajax({
        url: "../api/create_task.php",
        method: "POST",
        data: { task: task },
        dataType: "json",
        success: function(res) {

            if (res.status === "success") {

                $("#taskInput").val("");
                loadMyTasks();

                Swal.fire("Success", "Task added", "success");

            } else {
                Swal.fire("Error", "Failed to add task", "error");
            }
        }
    });
}

// (keeping your second function as-is)
function loadMyTasks() {

    $.get("../api/my_tasks.php", function(data) {

        let html = "";

        data.forEach(t => {
            html += `<li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>${t.task}</strong><br>
                    <small class="text-muted">
                        Added by ${t.admin_name ? t.admin_name : 'System'}
                    </small>
                </div>
                <span class="badge bg-info text-dark">${t.status}</span>
            </li>`;
        });

        $("#myTasks").html(html);

    }, "json");
}
</script>

</body>
</html>