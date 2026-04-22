<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

<h2>Register</h2>

<input type="text" id="name" placeholder="Name"><br><br>
<input type="email" id="email" placeholder="Email"><br><br>
<input type="password" id="password" placeholder="Password"><br><br>

<button onclick="register()">Register</button>

<script>
function register() {

    $.ajax({
        url: "../api/register.php",
        method: "POST",
        data: {
            name: $("#name").val(),
            email: $("#email").val(),
            password: $("#password").val()
        },
        dataType: "json",
        success: function(res) {
            if (res.status === "success") {
                Swal.fire("Success", "Registered successfully", "success");
                window.location.href = "login.php";
            } else {
                Swal.fire("Error", res.message, "error");
            }
        }
    });

}
</script>

</body>
</html>