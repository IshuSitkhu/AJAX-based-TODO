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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

<h2>Welcome Staff: <?php echo $_SESSION['name']; ?></h2>

<button onclick="logout()">Logout</button>

<hr>

<h3>My Tasks</h3>

<ul id="myTasks"></ul>

<script>
function loadMyTasks() {
    $.get("../api/fetch.php", function(data) {

        let html = "";

        data.forEach(t => {
            html += `<li>${t.task} - ${t.status}</li>`;
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
</script>

</body>
</html>