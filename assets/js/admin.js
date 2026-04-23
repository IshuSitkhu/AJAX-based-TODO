$(document).ready(function () {

    loadStats();
    loadUsersDropdown();

    // CREATE / UPDATE USER
    window.createUser = function () {

    let id = $("#editId").val();
    let name = $("#name").val().trim();
    let email = $("#email").val().trim();
    let password = $("#password").val();

    if (name.length < 3) {
        Swal.fire("Error", "Name must be at least 3 characters", "error");
        return;
    }

    let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        Swal.fire("Error", "Invalid email format", "error");
        return;
    }

    let strongPassword = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/;

    if (!id && !strongPassword.test(password)) {
        Swal.fire("Weak Password", "Must include uppercase, lowercase, number, special char", "error");
        return;
    }

    let url = id ? "../api/update_user.php" : "../api/create_user.php";

    let data = { id, name, email };

    // only send password if not empty
    if (password !== "") {
        data.password = password;
    }

    $.ajax({
        url: url,
        method: "POST",
        data: data,

        success: function (res) {

            try {
                let response = typeof res === "string" ? JSON.parse(res) : res;

                if (response.status === "success") {

                    Swal.fire("Success", response.message, "success");

                    $("#editId").val("");
                    $("#name").val("");
                    $("#email").val("");
                    $("#password").val("");

                    $("button[onclick='createUser()']").text("Submit");

                    loadUsers();
                } else {
                    Swal.fire("Error", response.message, "error");
                }

            } catch (e) {
                Swal.fire("Error", "Invalid server response", "error");
            }
        },

        error: function () {
            Swal.fire("Error", "Server not responding", "error");
        }
    });
};

    // SECTION CONTROL
    window.openCreate = function () {
        $(".section").hide();
        $("#create").show();
    };

    window.openUsers = function () {
        $(".section").hide();
        $("#users").show();
        loadUsers();
    };

    window.openTasks = function () {
        $(".section").hide();
        $("#tasks").show();
        loadTasks();
    };

    // EDIT USER

    window.editUser = function (id, name, email) {

        openCreate();

        $("#editId").val(id);
        $("#name").val(name);
        $("#email").val(email);

        $("button[onclick='createUser()']").text("Update User");
    };

    // LOAD USERS
    function loadUsers() {

    $.get("../api/get_all_users.php", function (data) {

        let html = "";

        data.forEach(function (u) {

            html += `
            <li class="list-group-item d-flex justify-content-between align-items-center">

                <div>
                    <strong>${u.name}</strong><br>
                    <small class="text-muted">${u.email}</small>
                </div>

                <div>
                    <span class="badge bg-${u.role === 'admin' ? 'danger' : 'primary'} me-2">
                        ${u.role}
                    </span>

                    <button class="btn btn-sm btn-warning me-1"
                        onclick="editUser(${u.id}, \`${u.name}\`, \`${u.email}\`)">
                        Edit
                    </button>

                    <button class="btn btn-sm btn-danger"
                        onclick="deleteUser(${u.id})">
                        Delete
                    </button>
                </div>

            </li>
            `;
        });

        $("#userList").html(html);

    }, "json");
}
    // DELETE USER
    window.deleteUser = function (id) {

        Swal.fire({
            title: "Are you sure?",
            text: "This user will be permanently deleted!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {

            if (result.isConfirmed) {

                $.ajax({
                    url: "../api/delete_user.php",
                    method: "POST",
                    data: { id: id },
                    dataType: "json",
                    success: function (res) {

                        if (res.status === "success") {
                            Swal.fire("Deleted!", "User removed", "success");
                            loadUsers();
                        } else {
                            Swal.fire("Error", "Delete failed", "error");
                        }
                    }
                });
            }
        });
    };

    // create task
window.createTask = function () {

    let task = $("#taskText").val().trim();
    let user_id = $("#assignUser").val();

    if (task === "") {
        Swal.fire("Error", "Task cannot be empty", "error");
        return;
    }

    $.ajax({
        url: "../api/create_task.php",
        method: "POST",
        data: { task, user_id },
        success: function () {

            Swal.fire("Success", "Task created", "success");

            $("#taskText").val("");

            loadTasks();
        }
    });
};

//delete task
window.deleteTask = function (id) {

    Swal.fire({
        title: "Delete task?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        confirmButtonText: "Delete"
    }).then((result) => {

        if (result.isConfirmed) {

            $.post("../api/delete.php", { id: id }, function () {

                Swal.fire("Deleted", "", "success");
                loadTasks();
            });
        }
    });
};

//edit task
window.editTask = function (id) {

    let newTask = prompt("Enter new task:");

    if (!newTask || newTask.trim() === "") return;

    $.post("../api/update_task.php", {
        id: id,
        task: newTask
    }, function () {

        Swal.fire("Updated", "", "success");
        loadTasks();
    });
};

    // LOAD TASKS
    function loadTasks() {

    $.get("../api/fetch.php", function (data) {

        let html = "";

        data.forEach(function (t) {

            html += `
            <li class="list-group-item d-flex justify-content-between align-items-center">

                <div>
                    <strong>${t.task}</strong><br>
                    <small class="text-muted">Status: ${t.status}</small>
                </div>

                <div>
                    <button class="btn btn-sm btn-warning me-1"
                        onclick="editTask(${t.id}, \`${t.task}\`)">
                        Edit
                    </button>

                    <button class="btn btn-sm btn-danger"
                        onclick="deleteTask(${t.id})">
                        Delete
                    </button>
                </div>

            </li>
            `;
        });

        $("#taskList").html(html);

    }, "json");
}

    // =========================
    // LOAD STATS
    // =========================
    function loadStats() {
        $.get("../api/task_stats.php", function (data) {
            $("#stats").html(`
                Total: ${data.total} <br>
                Completed: ${data.completed} <br>
                Pending: ${data.pending}
            `);
        }, "json");
    }

    function loadUsersDropdown() {

    $.get("../api/get_all_users.php", function (users) {

        let options = "";

        users.forEach(function (u) {
            options += `<option value="${u.id}">${u.name}</option>`;
        });

        $("#assignUser").html(options);

    }, "json");
}
});