<?php
include '../auth/auth.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Todo Tasks</title>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

<h2>Task Management</h2>

<!-- TASK INPUT -->
<input type="text" id="task" placeholder="Enter task">

<?php if ($_SESSION['role'] == 'admin') { ?>
    <select id="assignUser"></select>
<?php } ?>

<button id="addBtn">Add Task</button>

<hr>

<ul id="todoList"></ul>

<script src="../assets/js/script.js"></script>

</body>
</html>