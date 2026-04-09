$(document).ready(function () {

    loadTodos();

    function loadTodos() {
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
            }
        });
    }

    // ADD TODO
    $("#addBtn").click(function () {
        let task = $("#task").val();

        if (task.trim() === "") {
            alert("Task cannot be empty!");
            return;
        }

        $.ajax({
            url: "insert.php",
            method: "POST",
            data: { task: task },
            dataType: "json",
            success: function (res) {
                if (res.status === "success") {
                    $("#task").val("");
                    loadTodos();
                } else {
                    alert(res.message);
                }
            }
        });
    });

    // TOGGLE STATUS
    window.toggleTodo = function (id) {
        $.ajax({
            url: "toggle.php",
            method: "POST",
            data: { id: id },
            dataType: "json",
            success: function (res) {
                if (res.status === "success") {
                    loadTodos();
                }
            }
        });
    };

    // DELETE TODO
    window.deleteTodo = function (id) {
        $.ajax({
            url: "delete.php",
            method: "POST",
            data: { id: id },
            dataType: "json",
            success: function (res) {
                if (res.status === "success") {
                    loadTodos();
                } else {
                    alert(res.message);
                }
            }
        });
    };

    
    window.editTodo = function (id, task) {
        console.log("EDIT CLICKED", id, task);

        $("#editInput").val(task);
        $("#editModal").css("display", "flex");

        window.editId = id;
    };

    $("#saveEdit").click(function () {

        console.log("SAVE CLICKED");

        let updatedTask = $("#editInput").val();

        if (updatedTask.trim() === "") {
            alert("Task cannot be empty");
            return;
        }

        $.ajax({
            url: "edit.php",
            method: "POST",
            data: {
                id: window.editId,
                task: updatedTask
            },
            dataType: "json",
            success: function (res) {

                console.log("RESPONSE:", res);

                if (res.status === "success") {
                    $("#editModal").hide();
                    loadTodos();
                } else {
                    alert(res.message);
                }
            }
        });
    });

    $("#cancelEdit").click(function () {
        $("#editModal").hide();
    });

});