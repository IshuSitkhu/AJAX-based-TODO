<?php
include '../auth/auth.php';

if ($_SESSION['role'] != 'admin') {
    die("Access Denied");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Users</title>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

<h2>User Management</h2>

<table border="1" width="100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
        </tr>
    </thead>

    <tbody id="userTable"></tbody>
</table>

<script>
function loadUsers() {
    $.get("../api/get_all_users.php", function(data) {

        let html = "";

        data.forEach(user => {
            html += `
                <tr>
                    <td>${user.id}</td>
                    <td>${user.name}</td>
                    <td>${user.email}</td>
                    <td>${user.role}</td>
                </tr>
            `;
        });

        $("#userTable").html(html);
    }, "json");
}

loadUsers();
</script>

</body>
</html>