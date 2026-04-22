<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

<div class="login-container">
    <h2>Login</h2>

    <input type="email" id="email" placeholder="Email"><br><br>
    <input type="password" id="password" placeholder="Password"><br><br>

    <button onclick="login()">Login</button>
</div>

<script>
function login() {

    let email = $("#email").val();
    let password = $("#password").val();

    if (email == "" || password == "") {
        Swal.fire("Error", "All fields required", "error");
        return;
    }

    $.ajax({
        url: "../api/login.php",
        method: "POST",
        data: { email, password },
        dataType: "json",
        success: function(res) {

            if (res.status === "success") {
                window.location.href = "dashboard.php";
            } else {
                Swal.fire("Error", res.message, "error");
            }
        }
    });
}
</script>

</body>
</html>