$(document).ready(function () {

    loadTodos();

    //  Toast setup
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 1500
    });

    //  Loader functions
    function showLoader() {
        $("#loader").show();
    }

    function hideLoader() {
        $("#loader").hide();
    }

    function loadTodos() {
        showLoader();

        $.ajax({
            url: "fetch.php",
            method: "GET",
            dataType: "json",
            success: function (todos) {

                let html = "";

                todos.forEach(function (todo) {

                    let statusText = todo.status === "completed"
                        ? "Completed"
                        : "Pending";

                    let actionButton = todo.status === "completed"
                        ? `<button class="undo" onclick="toggleTodo(${todo.id})">Undo</button>`
                        : `<button class="done" onclick="toggleTodo(${todo.id})">Done</button>`;

                    html += `
                        <li>
                            <span class="task-text">
                                ${todo.task} <small>${statusText}</small>
                            </span>

                            <div class="actions">
                                ${actionButton}
                                <button class="edit" onclick="editTodo(${todo.id}, \`${todo.task}\`)">Edit</button>
                                <button class="delete" onclick="deleteTodo(${todo.id})">Delete</button>
                            </div>
                        </li>
                    `;
                });

                $("#todoList").html(html);
                hideLoader();
            },
            error: function () {
                hideLoader();
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Failed to load todos"
                });
            }
        });
    }

    // ADD TODO
    $("#addBtn").click(function () {
        let task = $("#task").val();

        if (task.trim() === "") {
            Swal.fire({
                icon: "warning",
                title: "Oops...",
                text: "Task cannot be empty!"
            });
            return;
        }

        showLoader();

        $.ajax({
            url: "insert.php",
            method: "POST",
            data: { task: task },
            dataType: "json",
            success: function (res) {
                hideLoader();

                if (res.status === "success") {
                    $("#task").val("");
                    loadTodos();

                    Toast.fire({
                        icon: "success",
                        title: "Task added"
                    });

                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: res.message
                    });
                }
            },
            error: function () {
                hideLoader();
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Insert failed"
                });
            }
        });
    });

    // TOGGLE STATUS
    window.toggleTodo = function (id) {
        showLoader();

        $.ajax({
            url: "toggle.php",
            method: "POST",
            data: { id: id },
            dataType: "json",
            success: function (res) {
                hideLoader();

                if (res.status === "success") {
                    loadTodos();

                    Toast.fire({
                        icon: "success",
                        title: "Status updated"
                    });
                }
            },
            error: function () {
                hideLoader();
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Toggle failed"
                });
            }
        });
    };

    // DELETE TODO
    window.deleteTodo = function (id) {

        Swal.fire({
            title: "Are you sure?",
            text: "This task will be deleted!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {

            if (result.isConfirmed) {

                showLoader();

                $.ajax({
                    url: "delete.php",
                    method: "POST",
                    data: { id: id },
                    dataType: "json",
                    success: function (res) {
                        hideLoader();

                        if (res.status === "success") {
                            loadTodos();

                            Toast.fire({
                                icon: "success",
                                title: "Deleted"
                            });

                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: res.message
                            });
                        }
                    },
                    error: function () {
                        hideLoader();
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: "Delete failed"
                        });
                    }
                });

            }

        });
    };

    // EDIT
    window.editTodo = function (id, task) {
        $("#editInput").val(task);
        $("#editModal").fadeIn(150).css("display", "flex");
        window.editId = id;
    };

    $("#saveEdit").click(function () {

        let updatedTask = $("#editInput").val();

        if (updatedTask.trim() === "") {
            Swal.fire({
                icon: "warning",
                title: "Oops...",
                text: "Task cannot be empty"
            });
            return;
        }

        showLoader();

        $.ajax({
            url: "edit.php",
            method: "POST",
            data: {
                id: window.editId,
                task: updatedTask
            },
            dataType: "json",
            success: function (res) {
                hideLoader();

                if (res.status === "success") {
                    $("#editModal").css("display", "none");
                    loadTodos();

                    Toast.fire({
                        icon: "success",
                        title: "Updated"
                    });

                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: res.message
                    });
                }
            },
            error: function () {
                hideLoader();
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Update failed"
                });
            }
        });
    });

    $("#cancelEdit").click(function () {
        $("#editModal").css("display", "none");
    });

});